function initPagination() {
    // Pagination feature
    var currentPage = 1;
    var rowsPerPage = parseInt($("#rowsPerPage").val()); // Number of rows per page
    var $tableRows = $(".list-row");
    var totalPages = Math.ceil($tableRows.length / rowsPerPage);

    function showPage(page) {
        var start = (page - 1) * rowsPerPage;
        var end = start + rowsPerPage;

        $tableRows.hide().slice(start, end).show();

        // Removes existing page numbers
        $(".page-item.pageNumber").remove();

        // Finds which page numbers to visualize
        var startPage = Math.max(1, currentPage - 1);
        var endPage = Math.min(startPage + 2, totalPages);

        // Adds page numbers found at markup HTML
        for (var i = startPage; i <= endPage; i++) {
            var $li = $("<li>", { class: "page-item pageNumber" });
            var $link = $("<a>", { class: "page-link", href: "#", text: i });
            if (i === currentPage) {
                $li.addClass("active");
            }
            $li.append($link);
            $li.insertBefore("#nextPage");
        }
    }

    function goToPreviousPage() {
        if (currentPage > 1) {
            currentPage--;
            showPage(currentPage);
        }
    }

    function goToNextPage() {
        if (currentPage < totalPages) {
            currentPage++;
            showPage(currentPage);
        }
    }

    // Aggiorna il numero di righe per pagina quando viene selezionato un nuovo valore
    $("#rowsPerPage").on("change", function() {
        rowsPerPage = parseInt($(this).val());
        totalPages = Math.ceil($tableRows.length / rowsPerPage);
        showPage(currentPage);
    });

    showPage(currentPage);

    $("#previousPage").on("click", goToPreviousPage);
    $("#nextPage").on("click", goToNextPage);

    $(document).on("click", ".pageNumber", function() {
        var page = parseInt($(this).text());
        currentPage = page;
        showPage(currentPage);
    });

    showPage(currentPage);
}

$(document).ready(function () {
    initPagination();
});