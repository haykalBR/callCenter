import * as React from 'react';
import * as ReactDOM from 'react-dom';

export default class DefaultComponent extends React.Component{
    constructor(props){
        super(props);
    }

    render(){
        return <React.Fragment>
            <p>React is working fine !</p>
        </React.Fragment>
    }
}

ReactDOM.render(<DefaultComponent />, document.getElementById('react-test'));

