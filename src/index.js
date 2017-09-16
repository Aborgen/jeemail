import React                 from 'react';
import ReactDOM              from 'react-dom';
import Email                 from './Email';
import registerServiceWorker from './registerServiceWorker';
import './Styles/stylesheets/screen.css';

ReactDOM.render(<Email />, document.getElementById('root'));
registerServiceWorker();
