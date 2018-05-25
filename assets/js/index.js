import React                 from 'react';
import ReactDOM              from 'react-dom';
import Jeemail               from './Jeemail';
import registerServiceWorker from './registerServiceWorker';
import { BrowserRouter as Router } from 'react-router-dom';

ReactDOM.render((
    <Router>
        <Jeemail />
    </Router>), document.getElementById('root'));
registerServiceWorker();
