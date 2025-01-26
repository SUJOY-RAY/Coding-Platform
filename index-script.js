function togglePanel(panel) {
    var panelElement = document.querySelector(`.${panel}`);
    
    if (panelElement.style.display === "none") {
      panelElement.style.display = "block";  
    } else {
      panelElement.style.display = "none";  
    }
  
    adjustLayout();
  }
  
  function adjustLayout() {
    var panels = document.querySelectorAll('.pane');
  
    let visiblePanels = Array.from(panels).filter(panel => panel.style.display !== "none");
  
    let remainingWidth = 100 / visiblePanels.length;  

    visiblePanels.forEach(panel => {
      panel.style.flex = `0 0 ${remainingWidth}%`;
    });
  }
  