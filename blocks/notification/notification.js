import { getCookie, setCookie } from '../../resources/js/plugins/helperFunctions'

~($ => {  
  const notifications = document.querySelectorAll('.notification')

  //takes some text and turns it into a number
  function simpleHash(text){
    var hash = 0;
    if (text.length == 0) {
      return hash;
    }
    for (var i = 0; i < text.length; i++) {
      var char = text.charCodeAt(i);
      hash = ((hash<<5)-hash)+char;
      hash = hash & hash; // Convert to 32bit integer
    }
    return hash;
  }

  //displays a notification if it has never been dismissed before
  function displayNotificationsIfCookieNotFound(){
    notifications.forEach(el => {
      const hash = simpleHash(el.querySelector('.editor-content').innerText.replace(/\s+/g, ''))
      const cookie = getCookie('notification-' + hash)
      if(!cookie){
        el.style.display = 'block'
      }
    })
  }

  //sets a cookie and closes notification
  function setCookieAndCloseNotification(e){
    const parent = e.target.closest('.notification')
    const hash = simpleHash(parent.querySelector('.editor-content').innerText.replace(/\s+/g, ''))
    setCookie('notification-'+hash, 1, 365)
    parent.style.display = 'none'
  }

  //only show the cookie if they haven't dismissed it before
  function init(){
    if(notifications.length){
      displayNotificationsIfCookieNotFound()
      document.querySelectorAll('.notification--close').forEach(b => b.addEventListener('click', setCookieAndCloseNotification))
    }
  }

  $(document).ready(init)
})(jQuery)