(function($){

	function getPosition(element) {
		var xPosition = 0;
		var yPosition = 0;

		while(element) {
			xPosition += (element.offsetLeft - element.scrollLeft + element.clientLeft);
			yPosition += (element.offsetTop - element.scrollTop + element.clientTop);
			element = element.offsetParent;
		}

		return { x: xPosition, y: yPosition };

	}

	$(document).ready(function(){

		const dashProposals = document.getElementById('lu-display-orders-dash');
		const createProposalEle = document.getElementById('lu-create-proposal');

		if( !dashProposals && !createProposalEle ){
			console.log('Doesnt exist page');
			return;
		}

		const dashProposalsPosition = document.getElementById('lu-display-orders-dash').offsetTop;
		const createProposal = createProposalEle;

		const createProposalPosition = createProposal.offsetTop;
		const createProposalHeight = createProposal.offsetHeight;
		const topUntilEndCreateProposal = createProposalPosition + createProposalHeight;
		const footerSitePosition = document.getElementById('lu-footer-entire-site').offsetTop;

		document.addEventListener('scroll', function (event) {

			if((topUntilEndCreateProposal + window.scrollY) >= (dashProposalsPosition + 120)){
				createProposal.style.width = '35%';
				createProposal.style.top = '20px';
			} else {
				createProposal.style.width = '50vw';
				createProposal.style.top = '100px';
			}

			if((topUntilEndCreateProposal + window.scrollY) >= (footerSitePosition)){
				createProposal.style.display = 'none';
			} else {
				createProposal.style.display = 'block';
			}
		}, true /*Capture event*/);
	});

})(jQuery);
