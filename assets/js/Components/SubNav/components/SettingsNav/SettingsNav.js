import React, { Component } from 'react';
import { Switch, Route }              from 'react-router-dom';

class SettingsNav extends Component {

    render() {
        return (
            <span style = {{ display: "inline-block" }} >
                <Switch>
                    <Route exact path = "/settings" render = {
                        () => <h1>Settings</h1>
                    } />
                    <Route path = "/settings/contacts" render = {
                        () => <h1>Contacts</h1>
                    } />
                    <Route path = "/settings/blocked" render = {
                        () => <h1>Blocked</h1>
                    } />
                    <Route path = "/settings/themes" render = {
                        () => <h1>Themes</h1>
                    } />
                </Switch>
            </span>
        );
    }
}

export default SettingsNav;
