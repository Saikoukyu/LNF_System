document.querySelector('.menu-toggle').addEventListener('click', function() {
  var sidebar = document.querySelector('.sidebar');
  var content = document.querySelector('.content');
  var isOpen = sidebar.style.transform === 'translateX(0%)';

  if (isOpen) {
      sidebar.style.transform = 'translateX(-100%)';
      content.style.marginLeft = '0';
  } else {
      sidebar.style.transform = 'translateX(0%)';
      content.style.marginLeft = '250px';
  }
});

document.querySelector('.dropdown-toggle').addEventListener('click', function() {
  var dropdown = this.parentElement;
  dropdown.classList.toggle('open');
});

    const logoutButton = document.getElementById('logoutButton');

    logoutButton.addEventListener('click', function() {
        window.location.href = 'NU_LoginPage.html';
    });