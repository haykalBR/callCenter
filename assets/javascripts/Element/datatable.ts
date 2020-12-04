class Datatable extends HTMLElement {
    constructor() {
      super(); 
      let shadow = this.attachShadow({mode: 'open'});
      let table = document.createElement('table');
      table.setAttribute('class', 'table table-striped table-bordered');
      shadow.appendChild(table);
    }
}

export default Datatable;