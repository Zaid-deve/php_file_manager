
const create_dir_btn = document.querySelector('.btn-create-folder'),
    create_dir_con = document.querySelector(".add-dir-con"),
    create_dir_inp = create_dir_con.querySelector("#dirname"),
    tree_con = document.querySelector(".sidebar-files"),
    sidebar_files = document.querySelector('.sidebar-files'),
    create_file_btn = document.querySelector('.btn-create-file'),
    create_file_con = document.querySelector(".add-file-con"),
    create_file_inp = create_file_con.querySelector('#filename')

create_file_btn.addEventListener('click', function () {
    create_file_con.classList.add('show')
    create_file_inp.focus()
})

create_dir_btn.addEventListener('click', function () {
    create_dir_con.classList.add('show')
    create_dir_inp.focus()
})

create_dir_inp.addEventListener('blur', function () {
    if (this.value != '') return;
    create_dir_con.classList.remove('show')
})

create_file_inp.addEventListener('blur', function () {
    if (this.value != '') return;
    create_file_con.classList.remove('show')
})


// function to add new dir to server

const add_dir = async function (dir) {
    let fullpath = dir.split('/'),
        basename = fullpath.pop(),
        maindir = fullpath.join('/'),
        path = basename;

    // dir location
    if (maindir != "") path = `${maindir}/${path}`

    // server response
    const response = await fetch(`php/mkdir.php?path=${path}`);

    if (response.ok) {
        const result = await response.text();
        if (result.trim() == 'success') {
            // get root directory and append new dir in it
            let new_wrapper = document.createElement('li');
            new_wrapper.innerHTML = `<div class='file-wrapper' onclick='viewFileContents(this)' oncontextmenu='showFileMenu(this,event)' data-path='${path}'>
                                        <img src='images/663341.png' alt='📄'>
                                        <span data-path='${path}'>${basename}</span>
                                        <button class='btn btn-toggle-file-wrapper' onclick='toggleFileTree(event,this)'><i class='ri-arrow-down-s-line'></i></button>
                             
                                        <div class='file-wrapper-menu' tabindex='-1'>
                                          <div class='file-wrapper-menu-opt' onclick='rename_con(this,event)'>
                                              <i class='ri-edit-box-fill'></i>
                                              <span>Rename</span>
                                          </div>
                                          <div class='file-wrapper-menu-opt' onclick='del_file(this,event)'>
                                              <i class='ri-delete-bin-3-line'></i>
                                              <span>Delete File</span>
                                          </div>
                                        </div>
                                     </div>
                                     <ul style='margin-left:12px;' class='hide'></ul>
                                     `;

            if (maindir == '') {
                sidebar_files.prepend(new_wrapper);
            }
            else {
                const parent_dir = document.querySelector(`[data-path='${maindir}']`);

                if (parent_dir !== null) {
                    parent_dir.nextElementSibling.prepend(new_wrapper);
                    parent_dir.nextElementSibling.classList.remove('hide')
                }
            }


            create_dir_inp.blur();
            create_dir_con.classList.remove('show')
            create_dir_inp.value = '';

        } else alert(result);
    } else alert('Something Went Wrong !')
}

create_dir_inp.addEventListener('keypress', function (e) {
    if (e.keyCode == 13) {
        if (create_dir_inp.value != '') {
            let path = create_dir_inp.value;
            if (curr_path != '') {
                path = `${curr_path}/${path}`;
            }
            add_dir(path)
        }
    }
})

// function to add new file to server
const add_file = async function (dir) {
    let fullpath = dir.split('/'),
        basename = fullpath.pop(),
        maindir = fullpath.join('/'),
        path = basename;

    // dir location
    if (maindir != "") path = `${maindir}/${path}`

    // server response
    const response = await fetch(`php/mkfile.php?path=${path}`);

    if (response.ok) {
        const result = await response.text();
        if (result.trim() == 'success') {
            // get root directory and append new dir in it
            let new_wrapper = document.createElement('li');
            new_wrapper.innerHTML = `<div class='file-wrapper' onclick='viewFile(this)' oncontextmenu='showFileMenu(this,event)' data-path='${path}'>
                                        <img src='images/images-removebg-preview.png' alt='📄'>
                                        <span data-path='${path}'>${basename}</span>
                                        
                            
                                        <div class='file-wrapper-menu' tabindex='-1'>
                                          <div class='file-wrapper-menu-opt' onclick='rename_con(this,event)'>
                                              <i class='ri-edit-box-fill'></i>
                                              <span>Rename</span>
                                          </div>
                                          <div class='file-wrapper-menu-opt' onlick='del_file(this,event)'>
                                              <i class='ri-delete-bin-3-line'></i>
                                              <span>Delete File</span>
                                          </div>
                                        </div>
                                     </div>`;

            if (maindir == '') {
                sidebar_files.prepend(new_wrapper);
            }
            else {
                const parent_dir = document.querySelector(`[data-path='${maindir}']`);

                if (parent_dir !== null) {
                    parent_dir.nextElementSibling.prepend(new_wrapper);
                    parent_dir.nextElementSibling.classList.remove('hide')
                }
            }


            create_file_inp.blur();
            create_file_con.classList.remove('show')
            create_file_inp.value = '';

        } else alert(result);
    } else alert('Something Went Wrong !')
}

create_file_inp.addEventListener('keypress', function (e) {
    if (e.keyCode == 13) {
        if (create_file_inp.value != '') {
            let path = create_file_inp.value;
            if (curr_path != '') {
                path = `${curr_path}/${path}`;
            }
            add_file(path)
        }
    }
})