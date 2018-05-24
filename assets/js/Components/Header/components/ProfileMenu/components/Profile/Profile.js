import React, { PureComponent } from 'react';
import PropTypes            from 'prop-types';

class  Profile extends PureComponent {

    getIconSize(imgSize) {
        const normalized = imgSize.toLowerCase();
        switch (normalized) {
            case "large":
                return "iconLarge";
            case "medium":
                return "iconMedium";
            case "small":
            default:
                return "iconSmall";
        }
    }

    render() {
        const { imgSize, user } = this.props;
        const imgClasses = imgSize !== undefined
            ? `profileIcon iconBorder ${this.getIconSize(imgSize)}`
            : `profileIcon iconBorder`;
        const icon = imgSize === "medium"
            ? user.icon.medium
            : user.icon.small;

        return (
            <div className = "profile">
                <span className = { imgClasses }>
                    <img src   = { icon }
                         title = {`${ user.name }'s profile icon`} />
                </span>
                <span>
                    <div className = "profileName">
                        { user.name }
                    </div>
                    <div className = "profileEmail">
                        { user.email }
                    </div>
                </span>
            </div>
        );
    }
}

export default Profile;

Profile.propTypes = {
    user: PropTypes.shape({
        name : PropTypes.string.isRequired,
        email: PropTypes.string.isRequired,
        icon : PropTypes.shape({
            small: PropTypes.string.isRequired,
            small: PropTypes.string.isRequired
        })
    }).isRequired
}
