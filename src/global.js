const settings_primary_state = {
    state: {
        active_connections: {},
        automated_email_report: {},
        enable_schema: true,
        schema: null,
    },
    settings: {
        active_connections: {},
        automated_email_report: {},
        enable_schema: true,
        schema: null,
    }
}

const widget_primary_state = {
    current_tab: 'overview',
    overview_summary_tab: 'embedded-badges',
    active_connections: [],
    badge_display: {
        widget_square: false,
        widget_basic: false,
        widget_icon: false,
        widget_rectangle: false,
        overall_cta_banner: false,
        overall_rectangle_embed: false,
        overall_rectangle_float: false,
        overall_narrow_embed: false,
        overall_narrow_float: false
    },
    widget_square: { widget_connections: [] },
    widget_basic: { widget_connections: [] },
    widget_icon: { widget_connections: [] },
    widget_rectangle: { widget_connections: [] },

    overall_rectangle_embed: {},
    overall_rectangle_float: { tablet: true, mobile: true, close_button: true },

    overall_narrow_embed: {},
    overall_narrow_float: { tablet: true, mobile: true, close_button: true },

    overall_popup: {},
    overall_cta_banner: {
        shadow: true,
        tablet: true,
        mobile: true,
        close_button: true,
        hide_on: [],
        button1: {
            show: true,
            text: 'Sign Up'
        }
    }
};

export { settings_primary_state, widget_primary_state };
