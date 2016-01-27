<script type="text/javascript">
    jQuery(document).ready(function () {
        // dynamic table
        oTable = jQuery('#{{ $id }}').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{ isset($options['route']) ? $options['route'] : '/' }}",
            "columns": [
                @foreach($columns as $name => $label)
                { 'data': '{{ $name }}' },
                @endforeach
            ]
        });
    });
</script>
