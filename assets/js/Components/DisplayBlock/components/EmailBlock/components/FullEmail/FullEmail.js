import React, { Component, Fragment } from 'react';
import PropTypes                      from 'prop-types';
import { Link }                       from 'react-router-dom';

import DropDown from '../../../../../DropDown/DropDown';
import Label    from './components/Label/Label';

class FullEmail extends Component {
    constructor() {
        super();
        this.labelCount = 0;
    }

    static get DROPDOWN_ONE() { return 0; }
    static get DROPDOWN_TWO() { return 1; }

    getTrigger(type) {
        const self = this.constructor;
        switch(type) {
            case self.DROPDOWN_ONE:
                return (
                    <Fragment>
                        <span>&#9660;</span>
                    </Fragment>
                );
            case self.DROPDOWN_TWO:
                return (
                    <Fragment>
                        <span>&#8629;</span>
                    </Fragment>
                );
        }
    }

    getContent(type, email = null) {
        const self = this.constructor;
        switch(type) {
            case self.DROPDOWN_ONE:
                return (
                    <table>
                        <tbody>
                            <tr>
                                <td>from:</td>
                                <td>
                                    { `${ email.content.id }<${ email.content.reply_to_email }>` }</td>
                            </tr>
                            <tr>
                                <td>to:</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>date:</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>subject:</td>
                                <td>{ email.content.subject }</td>
                            </tr>
                            <tr>
                                <td>mailed-by:</td>
                                <td>jeemail.com</td>
                            </tr>
                        </tbody>
                    </table>
                );
            case self.DROPDOWN_TWO:
                return (
                    <ol>
                        {
                            this.props.dropDownTwo.map((dropDownOne, i) => {
                                return (
                                    <li key = { i } >
                                        <Link to = { "" } >
                                            { dropDownOne.name }
                                        </Link>
                                    </li>
                                );
                            })
                        }
                    </ol>
                );
        }
    }

    /**
     * @return object | false
     */
    findEmail() {
        const { emails, checkState, match } = this.props;
        const { uid }                       = match.params;
        const emailArray = checkState(emails, match);
        if(!emailArray) {
            // The email label/category does not exist in the database
            return false;
        }

        return emailArray.find((email) => email.id == uid) || false;
    }

    generateLabels(labels) {
        if(typeof labels === 'object') {
            return <Label index = { this.labelCount++ }
                          label = { labels } />
        }

        return labels.map((label, i) =>
            <Label key   = { i }
                   index = { this.labelCount++ }
                   label = { label } />
        );
    }

    render() {
        const { componentName, message }  = this.props;
        const self = this.constructor;
        const email = this.findEmail();
        const { labels, defaultLabel } = email
            ? email.organizers
            : { labels: [], defaultLabel: {} };

        return (
            <div>
                { email &&
                <Fragment>
                    <header className = "emailHeader">
                        <div className = "emailHeaderTop">
                            <span><h1>{ email.content.subject }</h1></span>
                            <span className = "important">
                                <input type    = "checkbox"
                                       defaultChecked = { email.important } ></input>
                            </span>
                            <span className = "labels">
                                <ol>
                                    { this.generateLabels(defaultLabel) }
                                    { this.generateLabels(labels) }
                                </ol>
                            </span>
                        </div>
                        <div className = "emailHeaderBottom">
                            <div className = "headerBottomLeft">
                                <div>
                                    <span>{ email.id }</span>
                                    <span>
                                        &lt;{ email.content.reply_to_email }&gt;
                                    </span>
                                </div>
                                <div>
                                    <span>to me</span>
                                    <span>
                                        <DropDown parentName    = { componentName }
                                                  componentName = { "fullEmail" }
                                                  trigger       = { this.getTrigger(self.DROPDOWN_ONE) }
                                                  content       = { this.getContent(self.DROPDOWN_ONE, email) } />
                                    </span>
                                </div>
                            </div>
                            <div className = "headerBottomRight">
                                <span className = "date">
                                    <span>{ email.content.timestamp }</span>
                                    <span>({ new Date(parseInt(email.content.timestamp)).getDay()  } days ago)</span>
                                </span>
                                <span>
                                    <input type    = "checkbox"
                                           defaultChecked = { email.starred } ></input>
                                </span>
                                <span>
                                    <span>Reply</span>
                                    <span>
                                        <DropDown parentName    = { componentName }
                                                  componentName = { "fullEmail" }
                                                  trigger       = { this.getTrigger(self.DROPDOWN_TWO) }
                                                  content       = { this.getContent(self.DROPDOWN_TWO) } />
                                    </span>
                                </span>
                            </div>
                        </div>
                    </header>
                    <article>
                        <p>{ email.content.body }</p>
                    </article>
                </Fragment> ||
                <p>{ message }</p>
                }
            </div>
        );
    }

}

export default FullEmail;

FullEmail.defaultProps = {
    dropDownTwo: [
        {
            name: "Reply"
        },
        {
            name: "Forward"
        },
        {
            name: "Add to Contacts"
        },
        {
            name: "Delete"
        },
        {
            name: "Block"
        },
        {
            name: "Print"
        }
    ]
}
FullEmail.propTypes = {
    email: PropTypes.shape({
        id       : PropTypes.number.isRequired,
        important: PropTypes.bool.isRequired,
        starred  : PropTypes.bool.isRequired,
        category : PropTypes.shape({
            id        : PropTypes.number.isRequired,
            visibility: PropTypes.bool.isRequired,
            category  : PropTypes.shape({
                id  : PropTypes.number.isRequired,
                name: PropTypes.string.isRequired,
                slug: PropTypes.string.isRequired
            }).isRequired,
        }).isRequired,
        labels   : PropTypes.shape({
            id           : PropTypes.number.isRequired,
            defaultLabels: PropTypes.shape({
                id        : PropTypes.number.isRequired,
                visibility: PropTypes.bool.isRequired,
                label     : PropTypes.shape({
                    id  : PropTypes.number.isRequired,
                    name: PropTypes.string.isRequired,
                    slug: PropTypes.string.isRequired
                }).isRequired,
            }).isRequired,
            labels: PropTypes.arrayOf(PropTypes.shape({
                id        : PropTypes.number.isRequired,
                visibility: PropTypes.bool.isRequired,
                label     : PropTypes.shape({
                    id  : PropTypes.number.isRequired,
                    name: PropTypes.string.isRequired,
                    slug: PropTypes.string.isRequired
                }).isRequired,
            }).isRequired).isRequired // NOTE: It's possible there are no labels
        }).isRequired
    }).isRequired
}
