var downloadGraph = function() {
    var canvas = document.getElementById('lineChartCandidates');
    var pdf = new jsPDF('l');
    var newCanvas = document.createElement('canvas');
    var h3 = $('#lineChartCandidates').parent().parent().parent().parent().prev().find('h3:nth-child(2)');
    var summary = document.querySelector('.wrapper-content');
    var text = h3.html().trim();
    newCanvas.width = 800;
    newCanvas.height = 1000;
    
    var textWidth = pdf.getStringUnitWidth(text) * pdf.internal.getFontSize() / pdf.internal.scaleFactor;
    var textOffset = (pdf.internal.pageSize.width - textWidth) / 2;

    ctx = newCanvas.getContext('2d');
    ctx.clearRect(0, 0, canvas.width, canvas.height );
    ctx.fillStyle="#FFFFFF";
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    ctx.drawImage(canvas, 0, 0, 780, 300);
    imgData = newCanvas.toDataURL("image/png");
    
    // window.open(newCanvas.toDataURL("image/png"));
    pdf.setFontSize(16);
    // pdf.text(20, 20, h3.html().trim());
    // pdf.text(textOffset, 20, text);
    // pdf.addHTML(summary,0, 30, function() {
    //     pdf.save('web.pdf');
    // });
    // pdf.addImage(imgData, 'JPEG', 1, 30);
    html2canvas(summary, { 
        background: '#FFFFFF',
        width: summary.offsetWidth,
        height: summary.offsetHeight
    }).then(function(canvas) {
        ctx = canvas.getContext('2d');
        imgData = canvas.toDataURL('image/png');
        pdf.addImage(imgData, 'JPEG', 10, 10, 260, 200);
        canvas.width = 700;
        pdf.save('web.pdf');
    });
};