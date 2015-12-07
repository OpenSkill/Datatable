<table id="{{ $id }}" class="{{ $class }}">
    <colgroup>
        @for ($i = 0; $i < count($columns); $i++)
            <col class="con{{ $i }}"/>
        @endfor
    </colgroup>
    <thead>
    <tr>
        @foreach($columns as $i => $c)
            <th align="center" valign="middle" class="head{{ $i }}">{{ $c }}</th>
        @endforeach
    </tr>
    </thead>
</table>