$(document).ready(function() {
    function initializeSection(section) {
        const optionsList = $(section).find('.jqxs_optionsList');
        const chosenList = $(section).find('.jqxs_chosenList');
        const selectButton = $(section).find('.jqxs_selectButton');
        const removeButton = $(section).find('.jqxs_removeButton');
        const selectAllButton = $(section).find('.jqxs_selectAllButton');
        const removeAllButton = $(section).find('.jqxs_removeAllButton');
        const select = $(section).find('select');

        function adjustButtons() {
            if (optionsList.find('li:not(.jqxs_blank)').length > 0) {
                selectAllButton.prop('disabled', false);
                optionsList.find('li.jqxs_blank').remove();
            } else {
                selectAllButton.prop('disabled', true);
                if (optionsList.find('li.jqxs_blank').length === 0) {
                    optionsList.append('<li class="jqxs_blank" style="">&nbsp;</li>');
                }
            }

            if (chosenList.find('li:not(.jqxs_blank)').length > 0) {
                removeAllButton.prop('disabled', false);
                chosenList.find('li.jqxs_blank').remove();
            } else {
                removeAllButton.prop('disabled', true);
                if (chosenList.find('li.jqxs_blank').length === 0) {
                    chosenList.append('<li class="jqxs_blank" style="">&nbsp;</li>');
                }
            }
            updateSelect();
        }

        function updateSelect() {
            select.find('option').prop('selected', false); // Clear all selections

            chosenList.find('li:not(.jqxs_blank)').each(function() {
                const value = $(this).data('id');
                select.find(`option[value="${value}"]`).prop('selected', true);
            });
        }

        optionsList.on('click', 'li.jqxs_selected', function() {

            if (optionsList.find('.jqxs_focused').length > 0) {
                selectButton.prop('disabled', false);
            } else {
                selectButton.prop('disabled', true);
            }
        });

        selectButton.click(function() {
            optionsList.find('.jqxs_focused').each(function() {
                var item = $(this).removeClass('jqxs_focused');
                item.appendTo(chosenList).removeClass('jqxs_focused');
                item.click(removeHandler); // Add click handler for the moved item
            });
            adjustButtons();
            selectButton.prop('disabled', true);
            removeButton.prop('disabled', true);
        });

        function removeHandler() {
            if ($(this).hasClass('jqxs_focused')) {
                $(this).removeClass('jqxs_focused');
            } else {
                $(this).addClass('jqxs_focused');
            }
            if (chosenList.find('.jqxs_focused').length > 0) {
                removeButton.prop('disabled', false);
            } else {
                removeButton.prop('disabled', true);
            }
        }

        chosenList.on('click', 'li.jqxs_selected', removeHandler);

        removeButton.click(function() {
            chosenList.find('.jqxs_focused').each(function() {
                var item = $(this).removeClass('jqxs_focused');
                item.appendTo(optionsList).removeClass('jqxs_focused');
                item.click(addHandler); // Add click handler for the moved item
            });
            adjustButtons();
            removeButton.prop('disabled', true);
            selectButton.prop('disabled', true);
        });

        function addHandler() {
            if ($(this).hasClass('jqxs_focused')) {
                $(this).removeClass('jqxs_focused');
            } else {
                $(this).addClass('jqxs_focused');
            }
            if (optionsList.find('.jqxs_focused').length > 0) {
                selectButton.prop('disabled', false);
            } else {
                selectButton.prop('disabled', true);
            }
        }

        optionsList.find('li.jqxs_selected').click(addHandler);

        selectAllButton.click(function() {
            optionsList.find('li.jqxs_selected').each(function() {
                if (!$(this).hasClass('jqxs_blank')) {
                    var item = $(this);
                    item.appendTo(chosenList).removeClass('jqxs_focused');
                    item.click(removeHandler); // Add click handler for the moved item
                }
            });
            adjustButtons();
            selectButton.prop('disabled', true);
            removeButton.prop('disabled', true);
        });

        removeAllButton.click(function() {
            chosenList.find('li.jqxs_selected').each(function() {
                if (!$(this).hasClass('jqxs_blank')) {
                    var item = $(this);
                    item.appendTo(optionsList).removeClass('jqxs_focused');
                    item.click(addHandler); // Add click handler for the moved item
                }
            });
            adjustButtons();
            selectButton.prop('disabled', true);
            removeButton.prop('disabled', true);
        });

        adjustButtons();
        updateSelect();
    }

    // Initialize both sections
    initializeSection('#section1');
    initializeSection('#section2');
});
