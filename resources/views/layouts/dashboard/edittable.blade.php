
<script>
	(function () {
		var ta = document.createElement("textarea");
        ta.innerHTML = "{{ json_encode($pagination) }}";
        const txt = ta.innerText.replace(/\\/g, "\\\\").replace(/\n/g, "\\n");
        // window.TEXTO = txt;
		window.PAGINATION_DATA = JSON.parse(txt);
    })()
    window.HEADS = GetJsonEncodeData("{{ json_encode($heads) }}")

	/* function getTableData(getText) {
        var ta = document.createElement("textarea");
        ta.innerHTML = "{{ json_encode($pagination->items()) }}";
        const txt = ta.innerText.replace(/\\/g, "\\\\");
        if (getText) return txt;
		if (!window.TableData) return window.TableData = JSON.parse(txt);
		return window.TableData;
    } */
    // $("#table-data thead > tr").append("<th></th>");
    // $("#table-data tbody > tr").append('<td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-data-table">editar</button></td>')
    /* $(function() {
        $("#modal-data-table").on("show.bs.modal", function (e) {
            if(window.selectNewRow) window.selectNewRow($(e.relatedTarget).parents("tr").index());
        })
    }) */
</script>
