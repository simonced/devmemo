# regarding events

Some things work in some browsers but not all.  
Here is a list of problems we encountered during our developments.

## events related

**2017-02-06**

`document.activeElement` is not refering to the correct element in *Mac Safari*.  
replacement: `window.event.target`.  
This happened with an event fired by Bootstrap when closing a modal window.
