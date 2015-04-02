<!DOCTYPE html>
<html>

    ## include('partials.head') ##

<body>

    ## include('partials.header') ##

    <div id="side-panel">
        <ul>
            <li><a href="#">Home</a>
            <li><a href="#">Test</a>
        </ul>
    </div>

    <div id="content">
        <h1>Hello World</h1>
        <p>Hello World!</p>
        <p>My name is ## @name|John Doe ## and I'm ## @age|24 ## years old.</p>

        ## include('home.partials.test') ##

    </div>

    ## include('partials.footer') ##

</body>

</html>