/*
 Licence : CeCILL_V2.1
 Auteur(s) : Blanchard Stevan

 Descriptions : Code permetant le tri d'un talbeau Html via un script en JavaScript 

 fonctionnement:  la classe du tableau doit être 'table-sort' exemple : <table id='tab' class='table-sort'>

*/
function tableSortJs() {
    const columnData = [];
    const dictOfColumnIndexAndTableRow = {};
    for (let sortableTable of document.getElementsByTagName("table")) {
        if (sortableTable.classList.contains("table-sort")) {
            if (sortableTable.getElementsByTagName("thead").length === 0) {
                const the = document.createElement("thead");
                the.appendChild(sortableTable.rows[0]);
                sortableTable.insertBefore(the, sortableTable.firstChild);
            }

            const tableHead = sortableTable.querySelector("thead");
            const tableBody = sortableTable.querySelector("tbody");
            const tableHeadHeaders = tableHead.querySelectorAll("th");


            // Affiche un pointeur quand la souri survolle les en-tête du tableau.
            tableHead.addEventListener("mouseover", function (event) {
                setCursor(tableHead, "pointer");
            });
            function setCursor(tag, cursorStyle) {
                var elem;
                if (sortableTable.getElementsByTagName && (elem = tag)) {
                    if (elem.style) {
                        elem.style.cursor = cursorStyle;
                    }
                }
            }

            for (let [columnIndex, th] of tableHeadHeaders.entries("table")) {
                let timesClickedColumn = 0;

                th.addEventListener("click", function () {
                    timesClickedColumn += 1;

                    function getTableDataOnClick() {
                        const tableRows = tableBody.querySelectorAll("tr");
                        for (let [i, tr] of tableRows.entries()) {
                            if (
                                tr.querySelectorAll("td").item(columnIndex)
                                    .innerHTML !== ""
                            ) {
                                columnData.push(
                                    tr.querySelectorAll("td").item(columnIndex)
                                        .innerHTML +
                                        "#" +
                                        i
                                );
                                dictOfColumnIndexAndTableRow[
                                    tr.querySelectorAll("td").item(columnIndex)
                                        .innerHTML +
                                        "#" +
                                        i
                                ] = tr.innerHTML;
                            } else {
                                columnData.push("0#" + i);
                                dictOfColumnIndexAndTableRow["0#" + i] =
                                    tr.innerHTML;
                            }
                        }
                        function naturalSortAescending(a, b) {
                            return a.localeCompare(
                                b,
                                navigator.languages[0] || navigator.language,
                                { numeric: true, ignorePunctuation: true }
                            );
                        }
                        function naturalSortDescending(a, b) {
                            return naturalSortAescending(b, a);
                        }

                        // trie naturellement; par défaut ASC sauf si l'élément th contient 'order-by-desc' dans className.
                        if (typeof columnData[0] !== "undefined") {
                            if (
                                th.classList.contains("order-by-desc")  &&
                                timesClickedColumn === 1
                            ) {
                                columnData.sort(naturalSortDescending, {
                                    numeric: true,
                                    ignorePunctuation: true,
                                });
                            } else if (
                                th.classList.contains("order-by-desc")  &&
                                timesClickedColumn === 2
                            ) {
                                columnData.sort(naturalSortAescending, {
                                    numeric: true,
                                    ignorePunctuation: true,
                                });
                                timesClickedColumn = 0;
                            } else if (timesClickedColumn === 1) {
                                columnData.sort(naturalSortAescending);
                            } else if (timesClickedColumn === 2) {
                                columnData.sort(naturalSortDescending);
                                timesClickedColumn = 0;
                            }
                        }
                    }
                    getTableDataOnClick();
                    function returnSortedTable() {
                        const tableRows = tableBody.querySelectorAll("tr");
                        for (let [i, tr] of tableRows.entries()) {
                            tr.innerHTML =
                                dictOfColumnIndexAndTableRow[columnData[i]];
                        }
                        columnData.length = 0;
                    }
                    returnSortedTable();
                });
            }
        }
    }
}

if (
    document.readyState === "complete" ||
    document.readyState === "interactive"
) {
    tableSortJs();
} else if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", tableSortJs, false);
}
