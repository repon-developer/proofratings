const BadgeDisplay = (props) => {

    const badge_display = Object.assign({
        sites_square: false,
        sites_rectangle: false,
        verall Rating (Rectangle)
    }, props.badge_display);

    const update = (name) => {
        badge_display[name] = !badge_display[name];
        if ( typeof props.updateSettings === 'function') {
            props.updateSettings({badge_display})
        }
    }



    return (
        <React.Fragment>
            <table className="form-table">
                <tbody>
                    <tr>
                        <th scope="row" style={{ verticalAlign: "middle" }}>Sites (Square)</th>
                        <td>
                            <div className="proofratings-image-option">
                                <img src={`${proofratings.assets_url}images/widget-style1.png`} alt="Proofratings style" />
                                <label data-tab-button="#settings-badge-square">
                                    <input onChange={() => update('sites_square')} className="checkbox-switch checkbox-onoff" checked={badge_display.sites_square} type="checkbox" />
                                    Embed only
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" style={{ verticalAlign: "middle" }}>Sites (Rectangle)</th>
                        <td>
                            <div className="proofratings-image-option">
                                <img
                                    src={`${proofratings.assets_url}images/widget-style2.png`}
                                    alt="Proofratings style"
                                />
                                <label data-tab-button="#settings-badge-rectangle">
                                    <input className="checkbox-switch checkbox-onoff" onChange={() => update('sites_rectangle')} type="checkbox" checked={badge_display.sites_rectangle} /> Embed only
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" style={{ verticalAlign: "middle" }}>
                            Overall Rating (Rectangle)
                        </th>
                        <td>
                            <div className="proofratings-image-option">
                                <img
                                    src={`${proofratings.assets_url}images/floating-badge-style1.png`}
                                    alt="Proofratings style"
                                />
                                <label data-tab-button="#settings-overall-ratings-rectangle">
                                    <input
                                        name="proofratings_display_badge[overall_ratings_rectangle]"
                                        className="checkbox-switch checkbox-onoff"
                                        defaultValue="yes"
                                        type="checkbox"
                                        defaultChecked="checked"
                                    />
                                    Embed and/or float{" "}
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" style={{ verticalAlign: "middle" }}>
                            Overall Rating (Narrow)
                        </th>
                        <td>
                            <div className="proofratings-image-option">
                                <img
                                    src={`${proofratings.assets_url}images/floating-badge-style2.png`}
                                    alt="Proofratings style"
                                />
                                <label data-tab-button="#settings-overall-ratings-narrow">
                                    <input
                                        name="proofratings_display_badge[overall_ratings_narrow]"
                                        className="checkbox-switch checkbox-onoff"
                                        defaultValue="yes"
                                        type="checkbox"
                                        defaultChecked="checked"
                                    />
                                    Embed and/or float{" "}
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" style={{ verticalAlign: "middle" }}>
                            Overall Rating CTA Banner
                        </th>
                        <td>
                            <div className="proofratings-image-option">
                                <img
                                    src={`${proofratings.assets_url}images/cta-badge.png`}
                                    alt="Proofratings style"
                                />
                                <label data-tab-button="#settings-overall-ratings-cta-banner">
                                    <input
                                        name="proofratings_display_badge[overall_ratings_cta_banner]"
                                        className="checkbox-switch checkbox-onoff"
                                        defaultValue="yes"
                                        type="checkbox"
                                        defaultChecked="checked"
                                    />
                                    Float only{" "}
                                </label>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </React.Fragment>
    );
};

export default BadgeDisplay;
