self.onmessage = async(e) => {
    let { dataUrl, aspectRatio, maxWidth, maxHeight } = e.data;
    const result = await cropImage(dataUrl, aspectRatio);
    const resizedImage = await resizeImage(result, maxWidth, maxHeight);
    self.postMessage(`ok ${resizedImage}`);
}


function resizeImage(base64Str, maxWidth = 300, maxHeight = 250) {
    return new Promise((resolve) => {
        let img = new Image();
        img.src = base64Str;

        img.onload = () => {
            let canvas = document.createElement('canvas')
            const MAX_WIDTH = maxWidth
            const MAX_HEIGHT = maxHeight
            let width = img.width
            let height = img.height

            if (width > height) {
                if (width > MAX_WIDTH) {
                    height *= MAX_WIDTH / width
                    width = MAX_WIDTH
                }
            } else {
                if (height > MAX_HEIGHT) {
                    width *= MAX_HEIGHT / height
                    height = MAX_HEIGHT
                }
            }
            canvas.width = width
            canvas.height = height
            let ctx = canvas.getContext('2d')
            ctx.drawImage(img, 0, 0, width, height)
            resolve(canvas.toDataURL())
        }
    })
}

function cropImage(base64Str, aspectRatio = 1) {
    return new Promise((resolve) => {
        let img = new Image();
        img.src = base64Str;

        img.onload = () => {
            // let's store the width and height of our image
            const inputWidth = img.naturalWidth;
            const inputHeight = img.naturalHeight;

            // get the aspect ratio of the input image
            const imgAspectRatio = inputWidth / inputHeight;

            // if it's bigger than our target aspect ratio
            let outputWidth = inputWidth;
            let outputHeight = inputHeight;
            if (imgAspectRatio > aspectRatio) {
                outputWidth = inputHeight * aspectRatio;
            } else if (imgAspectRatio < aspectRatio) {
                outputHeight = inputWidth / aspectRatio;
            }

            // calculate the position to draw the image at
            const outputX = (outputWidth - inputWidth) * 0.5;
            const outputY = (outputHeight - inputHeight) * 0.5;

            // create a canvas that will present the output image
            const outputImage = document.createElement("canvas");

            // set it to the same size as the image
            outputImage.width = outputWidth;
            outputImage.height = outputHeight;

            // draw our image at position 0, 0 on the canvas
            const ctx = outputImage.getContext("2d");
            ctx.drawImage(img, outputX, outputY);
            resolve(outputImage.toDataURL());
        }
    })
}