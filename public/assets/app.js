// Basic JS for dismissible alerts via session flash
document.querySelectorAll('.alert [data-dismiss="alert"]').forEach(btn=>{
  btn.addEventListener('click',()=>btn.closest('.alert').remove());
});