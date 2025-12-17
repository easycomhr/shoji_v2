<form action="{{ route('admin.user.index') }}" method="get">
    <table class="w-100 ">
        <tr>
            <td class="w-50px">名前</td>
            <td class="w-25">
                <input type="text" class="form-control " name="s_name" value="{{ old('s_name', request()->get('s_name') ?? '') }}">
            </td>
            <td>
                <button type="submit" class="btn btn-default btn-form ml-2">検索</button>
            </td>
        </tr>

    </table>
</form>


