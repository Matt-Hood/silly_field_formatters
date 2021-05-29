var $ = jQuery;

$('#tooltip').qtip({
  content: 'My Silly Tooltip',
  position: {
    my: "bottom left",
    at: "right center",
    target: $("#tooltip")
  }
});

