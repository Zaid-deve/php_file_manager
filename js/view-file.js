const sidebar = document.querySelector(".sidebar"),
    path_view = document.querySelector('.file-view-list');
let curr_path = "",
    menu_visible = false;

async function loadPath(path) {
    path_view.textContent = "Fetching Files...";
    const response = await fetch(`php/get-files.php?path=${path}`);

    if (response.ok) {
        const data = await response.text();
        path_view.innerHTML = data;
    }
    else console.log(`Error: (${response.status})`)
}

window.addEventListener('popstate', function () {
    // get params
    let path = new URLSearchParams(location.search).get('path');

    if (path === null) {
        sidebar.classList.remove('hide')
        path_view.innerHTML = "";
    }
    else {
        curr_path = path
        loadPath(path)
    }
})
function toggleFileTree(e, btn) {
    e.stopPropagation()

    let parent = btn.parentElement,
        tree = parent.nextElementSibling


    e.target.classList.toggle('rotate')
    curr_path = parent.dataset.path;
    tree.classList.toggle('hide')
}

function viewFileContents(wrapper) {
    if (!menu_visible) {
        let path = wrapper.dataset.path

        sidebar.classList.add('hide');
        curr_path = path;


        if (path !== undefined || path != '') {
            let act_class = sidebar_files.querySelector('.active');
            if (act_class !== null) act_class.classList.remove('active')
            wrapper.classList.add('active')

            loadPath(path);
            history.pushState(null, null, `?path=${path}`)
        }
    }
}

function viewFile(wrapper) {
    let path = wrapper.dataset.path
    if (path != '') {
        location.href = "view.php?path=" + path;
    }
}

function showFileMenu(wrapper, e) {
    e.preventDefault()
    menu_visible = true


    let menu = wrapper.querySelector('.file-wrapper-menu')
    menu.classList.toggle('show')
    menu.addEventListener("blur", () => {
        menu.classList.remove('show')
        menu_visible = false
    })
}

// file mehu functions


async function rename_file(path, filename) {
    const response = await fetch(`php/rename_file.php?path=${path}&fname=${filename}`);
    if (response.ok) {
        const result = await response.text();
        if (result.trim() != 'success') alert(result)
    } else alert('Someting Went Wrong !');
}

function rename_con(wrapper, e) {
    e.stopPropagation()
    let filename = wrapper.parentElement.parentElement.querySelector('span'),
        path = filename.getAttribute('data-path')
    wrapper.parentElement.classList.remove('show')


    filename.contentEditable = true;
    filename.focus()
    filename.addEventListener('blur', function () {
        if (filename.textContent !== '') {
            filename.contentEditable = false
            rename_file(path, filename.textContent);
        }
    })
}

async function del_file(wrapper, e) {
    e.stopPropagation()
    let filename = wrapper.parentElement.parentElement.querySelector('span'),
        path = filename.getAttribute('data-path')

    if (path !== '') {
        const response = await fetch(`php/del_file.php?path=${path}`)
        if (response.ok) {
            const result = await response.text()
            if (result.trim() == 'success') {
                filename.parentElement.parentElement.remove()
                curr_path = '';
            } else alert(result)

        } else alert('Sonething Went Wrong !');
    }
}

const view_file_btns = document.querySelector('.file-view-btns'),
    btn_check_all = view_file_btns.children[0],
    btn_del_checked = view_file_btns.children[1],
    checked_files = new Set()

let is_all_cheked = false;
btn_check_all.addEventListener('click', function (e) {
    for (let i = 0; i < path_view.childElementCount; i++) {
        let fbox = path_view.children[i],
            cinp = fbox.lastElementChild

        if (!is_all_cheked) {
            cinp.checked = true
            checked_files.add(fbox.dataset.path)
        } else {
            cinp.checked = false
            checked_files.delete(fbox.dataset.path)
        }
    }

    if (is_all_cheked) {
        is_all_cheked = false
        view_file_btns.classList.remove('show')
        this.innerHTML = "<i class='ri-chekbox-line'></i>&nbsp;&nbsp;Check All"

    }
    else {
        is_all_cheked = true
        this.innerHTML = "<i class='ri-close-line'></i>&nbsp;&nbsp;De-Select All"
    }
})

btn_del_checked.addEventListener('click', async function (e) {
    if (checked_files.size > 0) {
        this.setAttribute('disabled',true)


        // prepare file to delete
        const data = Array.from(checked_files);
        const r = await fetch('php/delallfiles.php', {
            method:'POST',
            body: JSON.stringify({files: data})
        })

        if(r.ok)
        {
            const res = (await r.text()).trim();
            if(res==='success'){
                for(let c in path_view.children){
                    path_view.children[c].remove()
                }
            }
        }
        else alert(r.status);
    }
})

function checkFile(e) {
    e.stopPropagation()
    const target = e.target.parentElement,
        path = target.dataset.path

    if (path) {
        if (e.target.checked) checked_files.add(path);
        else checked_files.delete(path);
    }
    if (checked_files.size > 0) view_file_btns.classList.add('show')
    else view_file_btns.classList.remove('show')
}