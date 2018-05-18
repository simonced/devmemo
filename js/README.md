Some things work in some browsers but not all.  
Here is a list of problems we encountered during our developments.

# regarding events

## document.activeElement vs window.event.target

**2018-05-18**

Sometimes, IE will use Quirk mode in a different version and JS will return errors etc...  
To prevent that, always set `<meta http-equiv="x-ua-compatible" content="IE=edge">` in the `header` tag of the HTML pages!

**2017-02-06**

`document.activeElement` is not refering to the correct element in *Mac Safari*.  
replacement: `window.event.target`.  
This happened with an event fired by Bootstrap when closing a modal window.
