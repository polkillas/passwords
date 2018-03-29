const {crop} = require('easyimage');

function getElementPosition(el) {
    document.querySelector(el).scrollIntoView(false);

    let $el = $(el), data = $el.offset();
    data.width = $el.width();
    data.height = $el.height();

    return JSON.stringify(data);
}

module.exports = function() {
    return actor(
        {
            /**
             *
             * @param file     The file name
             * @param element  The element to capture
             * @param wait     Wait for x seconds before capturing
             * @param width    Width of the cropped area (Use element width by default)
             * @param height   Height of the cropped area (Use element height by default)
             * @returns {Promise<void>}
             */
            async captureElement(file, element, wait = 1, width = null, height = null) {

                if(wait) this.wait(wait);
                let data  = await this.executeScript(getElementPosition, element),
                    stats = JSON.parse(data);
                await this.captureWholePage(file, 0, true);

                if(width === null || width > stats.width) width = stats.width;
                if(height === null || height > stats.height) height = stats.height;

                await crop(
                        {
                            src       : `tests/codecept/output/${file}.png`,
                            dst       : `tests/codecept/output/${file}.png`,
                            y         : stats.top,
                            x         : stats.left,
                            cropWidth : width,
                            cropHeight: height
                        }
                    );
            },

            /**
             *
             * @param name  The file name
             * @param wait  Wait for x seconds before capturing
             * @param full  Capture full page
             */
            async captureWholePage(name, wait = 1, full = false) {
                this.moveCursorTo('#nextcloud');
                if(wait) this.wait(wait);
                await this.saveScreenshot(`${name}.png`, full);
            }
        }
    );
};
