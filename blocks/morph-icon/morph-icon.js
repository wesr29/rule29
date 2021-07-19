~($ => {
  function generateRandomPercents(){
    let load = []
    for (let i = 0; i < 8; i++){
      //between range equation: Math.random() * (max - min) + min
      let rand = Math.random() * (80 - 20) + 20
      load.push(`${rand}%`)
    }
    return load
  }
  
  function init(){
    if(document.querySelector('.morph-icon--circle')){
      document.querySelectorAll('.morph-icon--circle').forEach(circle => {
        let r = generateRandomPercents()
        circle.style.borderRadius = `${r[0]} ${r[1]} ${r[2]} ${r[3]} / ${r[4]} ${r[5]} ${r[6]} ${r[7]}`
      })
    }
  }
  
  $(document).ready(init)
})(jQuery)