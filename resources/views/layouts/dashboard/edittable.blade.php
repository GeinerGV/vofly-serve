
<script>
	function getTableData() {
		if (!window.TableData) return window.TableData = JSON.parse($('<textarea />').html("{{ json_encode($pagination->items()) }}").text());
		return window.TableData;
    }
    $("#table-data thead > tr").prepend("<th></th>");
    $("#table-data tbody > tr").prepend('<td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-data-table">editar</button></td>')
    /* $(function() {
        $("#modal-data-table").on("show.bs.modal", function (e) {
            if(window.selectNewRow) window.selectNewRow($(e.relatedTarget).parents("tr").index());
        })
    }) */
    $(function () {
        $("#form-data").on("submit", function(e) {
            //e.preventDefault();
        })
        
        $("#modal-data-table").on("show.bs.modal", function (e) {
            const id = $(e.relatedTarget).parents("tr").index();
		    window.lastRowData = getTableData()[id];
			$("#rowid").val(window.lastRowData.id);
            window.selectNewRow(id);
        })
    })
</script>