//checks if a variable is /pretty much/ empty
function empty(data) {
  if(typeof(data) == 'number' || typeof(data) == 'boolean') { 
    return false 
  }
  
  if(typeof(data) == 'undefined' || data === null) {
    return true 
  }
  
  if(typeof(data.length) != 'undefined'){
    return data.length == 0
  }

  var count = 0
  
  for(var i in data) {
    if(data.hasOwnProperty(i)){
      count ++
    }
  }

  return count == 0
}

//sets a cookie
function setCookie(cname, cvalue, exdays) {
  var d = new Date()
  d.setTime(d.getTime() + (exdays*24*60*60*1000))
  var expires = "expires="+ d.toUTCString()
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/"
}

//gets a cookie
function getCookie(cname) {
  var name = cname + "="
  var decodedCookie = decodeURIComponent(document.cookie)
  var ca = decodedCookie.split(';')
  for(var i = 0; i <ca.length; i++) {
    var c = ca[i]
    while (c.charAt(0) == ' ') {
      c = c.substring(1)
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length)
    }
  }
  return ""
}

//gets a GET string from the URL
function getURLParam(key){
  var params = {}
  var parser = document.createElement('a')
  parser.href = window.location.href
  var query = parser.search.substring(1)
  var vars = query.split('&')
  for (var i = 0; i < vars.length; i++) {
    var pair = vars[i].split('=')
    params[pair[0]] = decodeURIComponent(pair[1])
  }
  return typeof params[key] !== undefined ? params[key] : null
}

export {
  empty,
  setCookie,
  getCookie,
  getURLParam
}