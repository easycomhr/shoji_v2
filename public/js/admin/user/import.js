Ext.onReady(function () {

    var importForm;
    var fileField;
    var submitButton;
    var progressBar;

    // File validation
    function validateFile(file) {
        if (!file) {
            return { valid: false, message: 'Please select a file to import.' };
        }

        var fileName = file.name.toLowerCase();
        if (!fileName.endsWith('.xlsx') && !fileName.endsWith('.xls')) {
            return {
                valid: false,
                message: 'Invalid file format. Please select an Excel file (.xlsx or .xls).'
            };
        }

        // 10MB limit
        if (file.size > 10 * 1024 * 1024) {
            return {
                valid: false,
                message: 'File size too large. Maximum size allowed is 10MB.'
            };
        }

        return { valid: true, message: 'File is valid.' };
    }

    // Format file size
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        var k = 1024;
        var sizes = ['Bytes', 'KB', 'MB', 'GB'];
        var i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Update submit button state - SIMPLIFIED VERSION (always enabled)
    function updateSubmitButtonState() {
        if (!submitButton) return;

        // Always keep button enabled
        submitButton.setDisabled(false);
        submitButton.setText('Import File');
    }

    // Import function
    function doImport() {
        console.log('doImport() called');

        if (!importForm) {
            console.log('No import form found');
            return;
        }

        var form = importForm.getForm();
        if (!form.isValid()) {
            console.log('Form is not valid');
            Ext.Msg.alert('Validation Error', 'Please select a valid Excel file.');
            return;
        }

        // Enhanced file checking
        var fileInput = null;
        if (fileField.fileInputEl && fileField.fileInputEl.dom) {
            fileInput = fileField.fileInputEl.dom;
        } else if (fileField.el) {
            fileInput = fileField.el.dom.querySelector('input[type="file"]');
        }

        if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
            console.log('No file selected');
            Ext.Msg.alert('No File Selected', 'Please select a file to import.');
            return;
        }

        var file = fileInput.files[0];
        console.log('Selected file for import:', file.name, file.size);

        var validation = validateFile(file);
        if (!validation.valid) {
            console.log('File validation failed:', validation.message);
            Ext.Msg.alert('File Validation Error', validation.message);
            return;
        }

        console.log('All validations passed, showing confirmation dialog');

        Ext.Msg.confirm('Confirm Import',
            'Are you sure you want to import this file?<br/>' +
            '<b>File:</b> ' + file.name + '<br/>' +
            '<b>Size:</b> ' + formatFileSize(file.size),
            function(btn) {
                console.log('Confirmation dialog result:', btn);
                if (btn === 'yes') {
                    performImport();
                }
            }
        );
    }

    function performImport() {
        console.log('performImport() called');

        // Show progress
        if (progressBar) {
            progressBar.show();
            progressBar.updateProgress(0, 'Starting import...');
        }

        if (submitButton) {
            submitButton.setDisabled(true);
        }

        var submitUrl = (typeof IMPORT_URL !== 'undefined' ? IMPORT_URL : '/import');
        console.log('Submitting to URL:', submitUrl);

        // Check CSRF token
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        console.log('CSRF Token:', csrfToken);

        // Get file from file field
        var fileInput = fileField.fileInputEl.dom;
        var file = fileInput.files[0];
        console.log('File to upload:', file);

        // ONLY use FormData method (remove ExtJS form submit to prevent double submission)
        console.log('Using FormData method...');

        // Create FormData
        var formData = new FormData();
        formData.append('file', file);
        formData.append('_token', csrfToken);

        console.log('Sending FormData with fetch...');

        // Use fetch API
        fetch(submitUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => {
                console.log('Fetch response:', response);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);

                if (progressBar) {
                    progressBar.updateProgress(1, 'Import completed!');
                    setTimeout(function() {
                        progressBar.hide();
                    }, 2000);
                }

                if (submitButton) {
                    submitButton.setDisabled(false);
                }

                if (data.success) {
                    var message = 'Import completed successfully!';
                    if (data.imported_count !== undefined) {
                        message += '<br/>Records imported: ' + data.imported_count;
                    }

                    Ext.Msg.alert('Success', message, function() {
                        importForm.getForm().reset();
                        updateSubmitButtonState();
                    });
                } else {
                    var errorMessage = data.message || 'Import failed. Please try again.';
                    Ext.Msg.alert('Import Error', errorMessage);
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);

                if (progressBar) {
                    progressBar.hide();
                }

                if (submitButton) {
                    submitButton.setDisabled(false);
                }

                Ext.Msg.alert('Import Error', 'Network error occurred. Please try again.');
            });
    }

    function downloadExample() {
        try {
            var link = document.createElement('a');
            link.href = (typeof EXAMPLE_FILE_URL !== 'undefined' ? EXAMPLE_FILE_URL : '/example.xlsx');
            link.download = 'import_example.xlsx';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            Ext.Msg.alert('Download', 'Example file download started');
        } catch (e) {
            Ext.Msg.alert('Download Error', 'Failed to download example file');
        }
    }

    function refreshForm() {
        if (importForm) {
            importForm.getForm().reset();
            updateSubmitButtonState();

            if (progressBar && progressBar.isVisible()) {
                progressBar.hide();
            }
        }
    }

    // Create form
    importForm = Ext.create('Ext.form.Panel', {
        title: (typeof lblPageTitle !== 'undefined' ? lblPageTitle : 'Import Excel File'),
        frame: true,
        width: 600,
        bodyPadding: 20,
        renderTo: 'import-form',

        items: [
            {
                xtype: 'fieldset',
                title: 'File Import',
                items: [
                    {
                        xtype: 'component',
                        html: '<div style="background: #e8f4fd; padding: 10px; border-radius: 5px; margin-bottom: 15px;">' +
                            '<b>Instructions:</b><br/>' +
                            '1. Download the example file to see the required format<br/>' +
                            '2. Prepare your data in the same format<br/>' +
                            '3. Select your Excel file (.xlsx or .xls)<br/>' +
                            '4. Click Import to process the file</div>'
                    },
                    {
                        xtype: 'filefield',
                        name: 'file',
                        fieldLabel: 'Excel File',
                        labelWidth: 100,
                        buttonText: 'Browse...',
                        allowBlank: false,
                        anchor: '100%',
                        listeners: {
                            change: function(field, value) {
                                console.log('File field change event:', value);

                                // Enhanced file validation when file is selected
                                var fileInput = null;
                                if (field.fileInputEl && field.fileInputEl.dom) {
                                    fileInput = field.fileInputEl.dom;
                                } else if (field.el) {
                                    fileInput = field.el.dom.querySelector('input[type="file"]');
                                }

                                if (fileInput && fileInput.files && fileInput.files.length > 0) {
                                    var file = fileInput.files[0];
                                    console.log('Selected file:', file.name, file.size);

                                    var validation = validateFile(file);
                                    if (!validation.valid) {
                                        field.markInvalid(validation.message);
                                    } else {
                                        field.clearInvalid();
                                    }
                                }
                            }
                        }
                    }
                ]
            },
            {
                xtype: 'progressbar',
                itemId: 'progressBar',
                hidden: true,
                text: 'Ready to import...',
                margin: '15 0'
            },
            {
                xtype: 'component',
                html: '<div style="background: #fff3cd; padding: 8px; border-radius: 3px; margin-top: 15px;">' +
                    '<b>Note:</b> Maximum file size is 10MB. Only .xlsx and .xls files are supported.</div>'
            }
        ],

        buttons: [
            {
                text: 'Download Example',
                handler: downloadExample
            },
            '->',
            {
                text: 'Refresh',
                handler: refreshForm
            },
            {
                text: 'Import File',
                disabled: false,
                handler: doImport
            }
        ],

        listeners: {
            afterrender: function() {
                fileField = this.down('filefield');
                submitButton = this.down('button[handler=doImport]');
                progressBar = this.down('#progressBar');

                // Ensure button is always enabled
                updateSubmitButtonState();
            }
        }
    });

});