import { createStore, combineReducers } from "redux";

const ACTIONS = {
    UPDATE_STATE: "UPDATE_STATE",
    UPDATE_SETTINGS: "UPDATE_SETTINGS",
    UPDATE_CONNECTIONS: "UPDATE_CONNECTIONS",
    UPDATE_REPORT: "UPDATE_REPORT",
    UPDATE_SCHEMA: "UPDATE_SCHEMA",
};

const primary_state = {editing: false, error: null, loading: true, saving: false, location_id: null, settings_tab: 'connections' }

const stateReducer = (state = primary_state, action) => {
    switch (action.type) {
        case "UPDATE_STATE":
            return { ...state, ...action.payload };

        default:
            return state;
    }
};

const settings = {
    active_connections: {},
    automated_email_report: {},
    enable_shema: true,
    schema: `{
        "@context": "https://schema.org",
        "@type": "LocalBusiness",
        "name": "Proofratings",
        "image": "https://proofratings.com/wp-content/uploads/2021/08/Proofratings-site-header-logo.svg",
        "url": "https://proofratings.com/",
        "telephone": "(833) 662-0706",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "202 N. Dixon Ave.",
            "addressLocality": "Cary",
            "addressRegion": "NY",
            "postalCode": "27513",
            "addressCountry": "US"
        },
        "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": {{ratingValue}},
            "bestRating": "5",
            "ratingCount": {{ratingCount}}
        }
    }`
};

const settingsReducer = (state = settings, action) => {
    const { type, payload } = action

    switch (type) {
        case "UPDATE_SETTINGS":
            return { ...action.payload };

        case "UPDATE_CONNECTIONS":
            return { ...state, active_connections: { ...payload } };

        case "UPDATE_REPORT":
            return { ...state, ...payload };

        case "UPDATE_SCHEMA":
            return { ...state, schema: payload };            

        default:
            return state;
    }
};

const reducers = combineReducers({
    state: stateReducer,
    settings: settingsReducer
});

const store = createStore(reducers);

const update_store = (action) => {
    if ( action.type !== ACTIONS.UPDATE_SETTINGS ) {
        store.dispatch({ type: ACTIONS.UPDATE_STATE, payload: {editing: true} });    
    }

    store.dispatch(action);
}


export default store;

export { ACTIONS, update_store };
