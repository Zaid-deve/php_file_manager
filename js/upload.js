
const upload_sel = document.querySelector('#file'),
      upload_btn = document.querySelector('.btn-upload')

upload_sel.addEventListener("change",function(){
    upload_btn.disabled = false
})