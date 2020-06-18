
<script>
	function getTableData() {
		if (!window.TableData) return window.TableData = JSON.parse($('<textarea />').html("{{ json_encode($pagination->items()) }}").text());
		return window.TableData;
    }
    $("#table-data thead > tr").append("<th></th>");
    $("#table-data tbody > tr").append('<td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-data-table">editar</button></td>')
    /* $(function() {
        $("#modal-data-table").on("show.bs.modal", function (e) {
            if(window.selectNewRow) window.selectNewRow($(e.relatedTarget).parents("tr").index());
        })
    }) */
    $(function () {
        $("#form-data").on("submit", function(e) {
            if (e.target.checkValidity() === false) {
                e.preventDefault();
                e.stopPropagation();
                $("#form-data :invalid").addClass("is-invalid")
                $("#form-data :valid").removeClass("is-invalid")
            }
        })
        
        $("#modal-data-table").on("show.bs.modal", function (e) {
            const id = $(e.relatedTarget).parents("tr").index();
		    window.lastRowData = getTableData()[id];
			$("#rowid").val(window.lastRowData.id);
            window.selectNewRow(id);
        })
    })
</script>
