import store, { ACTIONS } from './Store';

const BadgeDisplay = (props) => {

    const badge_display = Object.assign({
        sites_square: false,
        sites_rectangle: false,
        overall_rectangle: {embed: false, float: false},
        overall_narrow: {embed: false, float: false},
    }, props.badge_display);

    const update_single = (name) => {
        badge_display[name] = !badge_display[name];
        store.dispatch({ type: ACTIONS.BADGE_DISPLAY, payload: badge_display });
    }

    const update_deep = (name, level2) => {
        badge_display[name][level2] = !badge_display[name][level2];
        store.dispatch({ type: ACTIONS.BADGE_DISPLAY, payload: badge_display });
    }

    const { sites_square, sites_rectangle, overall_rectangle, overall_narrow, overall_cta_banner } = badge_display;

    return (
        <React.Fragment>
            <table className="form-table">
                <tbody>
                    <tr>
                        <th scope="row" style={{ verticalAlign: "middle" }}>Sites (Square)</th>
                        <td>
                            <div className="proofratings-image-option">
                                <img src={`${proofratings.assets_url}images/widget-style1.png`} alt="Proofratings style" />
                                <label>
                                    <input onChange={() => update_single('sites_square')} className="checkbox-switch checkbox-onoff" checked={sites_square} type="checkbox" />
                                    Embed only
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" style={{ verticalAlign: "middle" }}>Sites (Rectangle)</th>
                        <td>
                            <div className="proofratings-image-option">
                                <img src={`${proofratings.assets_url}images/widget-style2.png`} alt="Proofratings style" />
                                <label>
                                    <input className="checkbox-switch checkbox-onoff" onChange={() => update_single('sites_rectangle')} type="checkbox" checked={sites_rectangle} /> Embed only
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" style={{ verticalAlign: "middle" }}>Overall Rating (Rectangle)</th>
                        <td>
                            <div className="proofratings-image-option">
                                <img src={`${proofratings.assets_url}images/floating-badge-style1.png`} alt="Proofratings style" />
                                <label style={{ marginRight: 30 }}>
                                    <input className="checkbox-switch checkbox-onoff" type="checkbox" checked={overall_rectangle?.embed} onChange={() => update_deep('overall_rectangle', 'embed')} /> Embed
                                </label>

                                <label>
                                    <input className="checkbox-switch checkbox-onoff" type="checkbox" checked={overall_rectangle?.float} onChange={() => update_deep('overall_rectangle', 'float')} /> Float
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" style={{ verticalAlign: "middle" }}>Overall Rating (Narrow)</th>
                        <td>
                            <div className="proofratings-image-option">
                                <img src={`${proofratings.assets_url}images/floating-badge-style2.png`} alt="Proofratings style" />
                                <label style={{marginRight: 30}}>
                                    <input
                                        type="checkbox"
                                        className="checkbox-switch checkbox-onoff"
                                        onChange={() => update_deep('overall_narrow', 'embed')}
                                        checked={overall_narrow?.embed}
                                    />
                                    Embed
                                </label>

                                <label>
                                    <input
                                        type="checkbox"
                                        className="checkbox-switch checkbox-onoff"
                                        onChange={() => update_deep('overall_narrow', 'float')}
                                        checked={overall_narrow?.float}
                                    />
                                    Float
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" style={{ verticalAlign: "middle" }}>Overall Rating CTA Banner</th>
                        <td>
                            <div className="proofratings-image-option">
                                <img src={`${proofratings.assets_url}images/cta-badge.png`} alt="Proofratings style" />
                                <label>
                                    <input
                                        type="checkbox"
                                        checked={overall_cta_banner}
                                        className="checkbox-switch checkbox-onoff"
                                        onChange={() => update_single('overall_cta_banner')}
                                    /> Float only
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
