import React                 from 'react';
import ReactDOM              from 'react-dom';
import Jeemail               from './Jeemail';
import registerServiceWorker from './registerServiceWorker';
import '../css/stylesheets/screen.css';

ReactDOM.render(<Jeemail />, document.getElementById('root'));
registerServiceWorker();
