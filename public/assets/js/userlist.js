window.addEventListener('load', function () {
    var resultsOnPage = document.getElementById('num_per_page');
    resultsOnPage.addEventListener('change', function () {
        resultsOnPage.parentNode.submit();
    });
});