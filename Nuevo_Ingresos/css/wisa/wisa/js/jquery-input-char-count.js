/*global jQuery, window, document*/
;(function ( $, window, document, undefined ) {
    
    'use strict';

    // Create the defaults once
    var pluginName = 'inputCharCount',
        defaults = {
            enableMaxAttributeName: 'data-input-char-count-enable-max',
            displayMaxValue: false,
            labelClasses: 'form-control-character-count',
            maxValue: -1
        },

        /*!
         * Small lodash subset. Would be overkill to require lodash completely.
         */
        _ = (function () {

            var
                // Constants.
                INFINITY = 1 / 0,

                // Private functions.
                baseToString = function (value) {

                    // Exit early for strings to avoid a performance hit in some environments.
                    if (typeof value == 'string') { return value; }

                    // We skip array and symbol conversion here:
                    // we just need scalar value conversion.

                    // Convert to string; preserve special values.
                    var result = (value + '');
                    return (result == '0' && (1 / value) == -INFINITY) ? '-0' : result;

                },
                toString = function (value) {
                    return (value == null) ? '' : baseToString(value);
                },

                /*! @see  underscore.string/toBoolean.js */
                boolMatch = function (value, matchers) {

                    var i,
                        matcher,
                        s = value.toLowerCase();

                    matchers = matchers ? [].concat(matchers) : [];

                    // Test all provided values.
                    for (i = 0; i < matchers.length; i += 1) {
                        matcher = matchers[i];
                        if (!matcher) { continue; }
                        if (matcher.test && matcher.test(value)) { return true; }
                        if (matcher.toLowerCase() === s) { return true; }
                    }

                    // No match.
                    return false;

                },

                toBoolean = function (value, trueValues) {

                    var s = toString(value),
                        trues = trueValues || ['true', '1'];

                    if (boolMatch(s, trues)) { return true; }

                    // No positive match.
                    return false;

                };

            return {

                /*!
                 * Converts `value` to a boolean. An empty string is returned for `null`
                 * and `undefined` values. The sign of `-0` is preserved.
                 *
                 * @param mixed value The value to convert.
                 * @param array trueValues The values considered to be true.
                 * @return bool
                 * @see https://github.com/epeli/underscore.string/blob/master/toBoolean.js
                 */
                toBoolean: toBoolean,

                /*!
                 * Converts `value` to a string. An empty string is returned for `null`
                 * and `undefined` values. The sign of `-0` is preserved.
                 *
                 * @param value The value to convert.
                 * @return string
                 */
                toString: toString

            };
        })(),

        /*!
         * Creates the label DOM element.
         *
         * @param object settings The plugin instance settings.
         * @param string labelText The label element's text.
         * @return jQuery The label DOM element.
         */
        labelFactory = function (settings, labelText) {
            return $('<span class="' + settings.labelClasses + '">' + labelText + '</span>');
        },

        /*!
         * Gets the character count label text.
         *
         * @param object settings The plugin instance settings.
         * @param jQuery source The source element.
         * @return string The text.
         */
        textFactory = function (settings, source) {

            var length = source.val().length, // Get current length.
                maxValue = settings.displayMaxValue ? settings.maxValue : undefined; // Get maximum length.

            // Assemble and return string.
            return (maxValue ? length + ' / ' + maxValue : length);

        };    

    // The actual plugin constructor.
    function Plugin(element, options) {

        // Initialize members.
        this.$el = element instanceof jQuery ? element : $(element);
        this.settings = $.extend({}, defaults, options) ;
        this._defaults = defaults;
        this._name = pluginName;

        // Call initializer.
        this.init();
    }

    // Prototype: Instance methods.
    $.extend(Plugin.prototype, {

        init: function() {
            this.initializeSettings();

            // Create label.
            var text = textFactory(this.settings, this.$el),
                label = labelFactory(this.settings, text),
                that = this;

            // Append label DOM element, initialize handler.
            this.$el.after(label);
            this.$el.on('input', function () {
                label.html(textFactory(that.settings, that.$el));
            });
        },

        initializeSettings: function() {

            // Prepare.
            var maxValue = this.$el.attr('maxlength'),
                displayMaxValueAttr = this.$el.attr(this.settings.enableMaxAttributeName),
                displayMaxValue = displayMaxValueAttr ? _.toBoolean(displayMaxValueAttr) : true;

            // Apply values.
            this.settings.maxValue = maxValue;
            this.settings.displayMaxValue = (maxValue && displayMaxValue);

        }

    });

    // A really lightweight plugin wrapper around the constructor.
    $.fn[pluginName] = function ( options ) {
        return this.each(function () {
            if (!$.data(this, 'plugin_' + pluginName)) {
                $.data(this, 'plugin_' + pluginName,
                new Plugin( this, options ));
            }
        });
    };

    // Auto initialize those form elements having the appropriate class.
    $(function () {

        $('input[type="text"].input-char-count, textarea.input-char-count').inputCharCount();

    });


})(jQuery, window, document);