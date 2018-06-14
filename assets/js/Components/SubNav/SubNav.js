import React, { Component } from 'react';
import { Switch, Route }    from 'react-router-dom';
import PropTypes            from 'prop-types';

import EmailNav   from './components/EmailNav/EmailNav';
import EmailViews from './components/EmailViews/EmailViews';
import InputTools from './components/InputTools/InputTools';
import Pages      from './components/Pages/Pages';
import Settings   from './components/Settings/Settings';

class SubNav extends Component {

    refresh() {
        // const beginning = Math.random() * (100 - 0) + 0,
        //       end       = Math.random() * (100 - 0) + 0;
        this.props.refresh(/*getEmails(beginning, end)*/);
    }


    selectionOpt(e) {
        this.props.selectionOpt(e.target.innerText);
    }

    render() {
        return (
            <div className = "subNav">
                <div className = "subNavPiece subNavLeft">
                    <EmailViews  componentName = "subNav" />
                    <Switch>
                        <Route path = "/email" component = { EmailNav } />
                        <Route path = "/settings" component = { EmailNav } />
                    </Switch>

                </div>
                <div className = "subNavPiece subNavRight">
                    <Route path = "/" render = {
                        (props) => {
                            const notSettings
                                = props.location.pathname !== "/settings";
                            return notSettings &&
                                <Pages componentName = "subNav" />
                        }
                    } />
                    <InputTools  componentName = "subNav" />
                    <Settings    componentName = "subNav" />
                </div>
            </div>
        );
    }
}

export default SubNav;

SubNav.propTypes = {
    refresh: PropTypes.func
}
