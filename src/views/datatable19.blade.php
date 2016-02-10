<script type="text/javascript">
    jQuery(document).ready(function () {
        // dynamic table
        oTable = jQuery('#{{ $id }}').dataTable({
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "{{ $endpoint }}",
            "aoColumns": [
                @foreach($columns as $name => $label)
                { 'mData': '{{ $name }}' },
                @endforeach
            ]
        });
    });
</script>
