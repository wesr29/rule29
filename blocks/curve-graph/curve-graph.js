~($ => {

  //return a bunch of { x: 0, y: 0 } coordinates
  function getPoints(selector){
    const points = []

    document.querySelectorAll('.curve-graph--' + selector).forEach(el => {
      const childPos = $(el).offset()
      const parentPos = $(el).parent().parent().offset()
      points.push({ 
        x: childPos.left - parentPos.left + (el.offsetWidth / 2), //divide size by 2 to center the line on the point
        y: childPos.top - parentPos.top + (el.offsetHeight / 2)
      })
    })

    return points
  }

  function curve(ctx, points){
    ctx.moveTo((points[0].x), points[0].y)

    for(var i = 0; i < points.length-1; i ++) {
      var x_mid = (points[i].x + points[i+1].x) / 2
      var y_mid = (points[i].y + points[i+1].y) / 2
      var cp_x1 = (x_mid + points[i].x) / 2
      var cp_x2 = (x_mid + points[i+1].x) / 2
      ctx.quadraticCurveTo(cp_x1,points[i].y ,x_mid, y_mid)
      ctx.quadraticCurveTo(cp_x2,points[i+1].y ,points[i+1].x,points[i+1].y)
    }
  }


  function getTallestYInPoints(points){
    let max = 0
    points.forEach(p => {
      if(p.y < max){
        max = p.y
      }
    })
    return max + 2
  }

  function init(){
    if(!$('.curve-graph--graph').length){
      return
    }

    const canvas = $('<canvas />').get(0)
    const ctx = canvas.getContext('2d')
    const points = getPoints('p1')

    canvas.height = document.querySelector('.curve-graph--graph').offsetHeight
    canvas.width = document.querySelector('.curve-graph--graph').offsetWidth

    var gradient=ctx.createLinearGradient(canvas.width / 2, getTallestYInPoints(points), canvas.width/2, canvas.height)
    gradient.addColorStop(0, "#00C389")
    gradient.addColorStop(1, "#00C389")

    ctx.beginPath()
    ctx.fillStyle = gradient

    //Add a fake starting and endpoint to make the fill work correctly
    points.unshift({ x: points[0].x, y: canvas.height })
    points.push({ x: points[ points.length - 1 ].x, y: canvas.height })

    curve(ctx, points)
    ctx.fillStyle = gradient
    ctx.fill()
    ctx.closePath()

    $('.curve-graph--graph').append(canvas)
  }

  $(document).ready(init)
})(jQuery)
