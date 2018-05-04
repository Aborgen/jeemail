import React, { Component } from 'react';

class Footer extends Component {

    render() {
        return (
            <div className="footer">
                <div className="footerContent">
                    <span className="footerPiece">
                        <div className="footerBlock">
                            <p>1.3 GB (8%) of 15 GB used</p>
                        </div>
                        <div className="footerBlock">
                            <a href="">Manage</a>
                        </div>
                    </span>
                    <span className="footerPiece">
                        <div className="footerInline">
                            <a href="">Terms</a>
                        </div>
                        <div className="footerInline">
                            <p>-</p>
                        </div>
                        <div className="footerInline">
                            <a href="">Privacy</a>
                        </div>
                    </span>
                    <span className="footerPiece">
                        <div className="footerBlock">
                            <p>Last account activity: 1 mil years</p>
                        </div>
                        <div className="footerBlock">
                            <a href="">Details</a>
                        </div>
                    </span>
                </div>
            </div>
        );
    }

}

export default Footer;
