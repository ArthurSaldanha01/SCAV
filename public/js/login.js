document.querySelectorAll('.toggle-password').forEach(function(btn){
  btn.addEventListener('click',function(){
    var input=document.querySelector(this.getAttribute('data-target'));
    if(!input)return;
    if(input.type==='password'){
      input.type='text';
      this.innerHTML='<i class="fas fa-eye-slash"></i>';
    }else{
      input.type='password';
      this.innerHTML='<i class="fas fa-eye"></i>';
    }
  });
});
