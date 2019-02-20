<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>add search</title>
</head>

<body>
<form onsubmit="return add()">
    <input name="url" value="1">
    <button type="submit">add</button>
</form>
<script>
search_url = 'http://lan.cpn.red/opensearch?debug[key]=bunny'
engine_url = document.getElementsByName('url')[0]
engine_url.value = search_url

function add() {
    window.external.AddSearchProvider(engine_url.value || search_url)
    return false
}

</script>
<style>
input {
    width: 100%;
}
</style>
</body>
</html>
