const fileView = document.querySelector('.file-view-list');

async function searchFile(inp) {
    const qry = inp.value.trim();

    if (qry != '') {
        // inp.disabled = true
        const response = await fetch(`php/search.php?path=${curr_path}&qry=${qry}`);
        if (response.ok) {
            const result = await response.text()
            fileView.innerHTML = result;
        }
    }
}