export default () => ({
    avatar: {
        ['@change']($event) {
            const img_ava = document.getElementById('avatar_img');
            let loader = document.querySelector('.loader');

            let options = {
                max_size: 5507566,
                aspectRatio: 1,
                maxHeight: 120,
                maxWidth: 120,
                loader,
                output: img_ava,
                hideableElems: []
            }

            this.handleFileUpload($event, 'avatar', options)
        }
    },

    cover: {
        ['@change']($event) {
            const cover = document.getElementById('cover_out');
            let loader = document.getElementById('loader_cover');
            let coverSVG = document.getElementById('cover-svg');

            let options = {
                max_size: 4507566,
                aspectRatio: 1.5,
                maxWidth: 500,
                maxHeight: 320,
                loader,
                output: cover,
                hideableElems: [coverSVG]
            }

            this.handleFileUpload($event, 'cover_photo', options);
        }
    },

    //image manipulation utility functions
    handleFileUpload(event, livewireProperty, options) {
        let {
            max_size,
            loader,
            aspectRatio,
            maxWidth,
            maxHeight,
            output,
            hideableElems
        } = options;

        const image = event.target.files[0];
        const IMG_TYPES = ['image/jpg', 'image/png', 'image/jpeg'];
        const MAX_FILE_SIZE = max_size ? max_size : 5242880;

        //check file type
        if (image.type && !IMG_TYPES.includes(image.type)) {
            this.$dispatch('open-alert', {
                alert_type: 'danger',
                message: 'only images of MIME types: JPG, JPEG and PNG are allowed'
            });

            return
        }

        //check file size for validity
        if (image.size > MAX_FILE_SIZE) {
            this.$dispatch('open-alert', {
                alert_type: 'danger',
                message: `only images less than ${ (MAX_FILE_SIZE/1000000).toFixed(1) }MB are allowed`
            });

            return
        }

        let reader = new FileReader();

        reader.onloadstart = () => {
            if (loader) {
                loader.style.display = 'flex';
            }
        }

        reader.onloadend = async() => {
            const result = await this.cropImage(reader.result, aspectRatio);
            const resizedImage = await this.resizeImage(result, maxWidth, maxHeight);

            $wire.upload(
                livewireProperty,
                this.b64toBlob(resizedImage),
                finishCallback = (uploadedFilename) => {
                    this.hideElements(loader, ...hideableElems);
                    this.displayImage(output, resizedImage)
                },
                errorCallback = () => {
                    this.$dispatch('open-alert', {
                        alert_type: 'danger',
                        message: 'unable to upload photo please try again'
                    })
                },
            )
        }

        reader.readAsDataURL(image);
    },

    resizeImage(base64Str, maxWidth = 300, maxHeight = 250) {
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
    },

    cropImage(base64Str, aspectRatio = 1) {
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
    },

    //dom helper functions
    hideElements(...elements) {
        if (!elements && !(elements instanceof Array)) {
            return
        }

        elements.forEach(element => {
            element.style.display = 'none';
        });
    },

    displayImage(output, image) {
        if (output && image) {
            output.classList.remove('hidden');
            output.src = `${image}`
            return
        }
    },

    b64toBlob(b64Data, sliceSize) {
        // Split the base64 string in data and contentType
        var block = b64Data.split(";");
        // Get the content type of the image
        contentType = block[0].split(":")[1] || '';
        // get the real base64 content of the file
        b64Data = block[1].split(",")[1]; // In this case "R0lGODlhPQBEAPeoAJosM...."

        sliceSize = sliceSize || 512;

        var byteCharacters = atob(b64Data);
        var byteArrays = [];

        for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
            var slice = byteCharacters.slice(offset, offset + sliceSize);

            var byteNumbers = new Array(slice.length);
            for (var i = 0; i < slice.length; i++) {
                byteNumbers[i] = slice.charCodeAt(i);
            }

            var byteArray = new Uint8Array(byteNumbers);

            byteArrays.push(byteArray);
        }

        var blob = new Blob(byteArrays, {
            type: contentType
        });

        return blob;
    }
})