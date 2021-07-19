~($ => {

  //return a bunch of { x: 0, y: 0 } coordinates
  function getPoints(selector){
    const points = []

    document.querySelectorAll('.line-graph--' + selector).forEach(el => {
      const childPos = $(el).offset()
      const parentPos = $(el).parent().parent().offset()
      points.push({ 
        x: childPos.left - parentPos.left + (el.offsetWidth / 2), //divide size by 2 to center the line on the point
        y: childPos.top - parentPos.top + (el.offsetHeight / 2)
      })
    })

    //add arbitrary/fake starting/last points for style purposes...
    points.unshift({
      x: points[0].x - 100,
      y: points[0].y 
    })
    points.push({
      x: points[points.length - 1].x + 100,
      y: points[points.length - 1].y
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

  function init(){
    if(!$('.line-graph--graph').length){
      return
    }

    const canvas = $('<canvas />').get(0)
    const ctx = canvas.getContext('2d')
    const points1 = getPoints('p1')
    const points2 = getPoints('p2')

    canvas.height = document.querySelector('.line-graph--graph').offsetHeight
    canvas.width = document.querySelector('.line-graph--graph').offsetWidth

    ctx.lineWidth = 3
    ctx.beginPath()
    ctx.strokeStyle = '#466C92'
    curve(ctx, points1)
    ctx.stroke()
    ctx.closePath()

    ctx.beginPath()
    ctx.strokeStyle = '#00C389'
    curve(ctx, points2)
    ctx.stroke()
    ctx.closePath()

    $('.line-graph--graph').append(canvas)
  }

  $(document).ready(init)
})(jQuery)
