export default (Alpine) => {
    Alpine.data('customFileUploadFormComponent', ({
        acceptedFileTypes,
        canReorder,
        canPreview,
        deleteUploadedFileUsing,
        getUploadedFileUrlsUsing,
        posterFileUrl,
        imageCropAspectRatio,
        imagePreviewHeight,
        imageResizeTargetHeight,
        imageResizeTargetWidth,
        loadingIndicatorPosition,
        panelAspectRatio,
        panelLayout,
        placeholder,
        maxSize,
        minSize,
        removeUploadedFileButtonPosition,
        removeUploadedFileUsing,
        reorderUploadedFilesUsing,
        shouldAppendFiles,
        state,
        uploadButtonPosition,
        uploadProgressIndicatorPosition,
        uploadUsing,
    }) => {
        return {
            fileKeyIndex: {},

            pond: null,

            shouldUpdateState: true,

            state,

            uploadedFileUrlIndex: {},

            init: async function() {
                this.pond = FilePond.create(this.$refs.input, {
                    acceptedFileTypes,
                    allowReorder: canReorder,
                    allowImagePreview: canPreview,
                    allowVideoPreview: canPreview,
                    allowAudioPreview: canPreview,
                    credits: false,
                    files: await this.getFiles(),
                    imageCropAspectRatio,
                    imagePreviewHeight,
                    imageResizeTargetHeight,
                    imageResizeTargetWidth,
                    itemInsertLocation: shouldAppendFiles ? 'after' : 'before',
                    ...(placeholder && { labelIdle: placeholder }),
                    maxFileSize: maxSize,
                    minFileSize: minSize,
                    styleButtonProcessItemPosition: uploadButtonPosition,
                    styleButtonRemoveItemPosition: removeUploadedFileButtonPosition,
                    styleLoadIndicatorPosition: loadingIndicatorPosition,
                    stylePanelAspectRatio: panelAspectRatio,
                    stylePanelLayout: panelLayout,
                    styleProgressIndicatorPosition: uploadProgressIndicatorPosition,
                    server: {
                        load: async(source, load) => {
                            let response = await fetch(source)
                            let blob = await response.blob()

                            load(blob)
                        },
                        process: (fieldName, file, metadata, load, error, progress) => {
                            this.shouldUpdateState = false

                            let fileKey = ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>
                                (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
                            )

                            uploadUsing(fileKey, file, (fileKey) => {
                                this.shouldUpdateState = true

                                load(fileKey)
                            }, error, progress)
                        },
                        remove: async(source, load) => {
                            let fileKey = this.uploadedFileUrlIndex[source] ?? null

                            if (!fileKey) {
                                return
                            }

                            await deleteUploadedFileUsing(fileKey)

                            load()
                        },
                        revert: async(uniqueFileId, load) => {
                            await removeUploadedFileUsing(uniqueFileId)

                            load()
                        },
                    },
                })

                this.$watch('state', async() => {
                    if (!this.shouldUpdateState) {
                        return;
                    }

                    // We don't want to overwrite the files that are already in the input, if they haven't been saved yet.
                    if (Object.values(this.state).filter((file) => file.startsWith('livewire-file:')).length) {
                        return
                    }

                    this.pond.files = await this.getFiles()
                })

                this.pond.on('reorderfiles', async(files) => {
                    const orderedFileKeys = files
                        .map(file => file.source instanceof File ? file.serverId : this.uploadedFileUrlIndex[file.source] ?? null) // file.serverId is null for a file that is not yet uploaded
                        .filter(fileKey => fileKey)

                    await reorderUploadedFilesUsing(shouldAppendFiles ? orderedFileKeys : orderedFileKeys.reverse())
                })

                this.pond.on('processfilestart', async() => {
                    this.dispatchFormEvent('file-upload-started')
                })

                this.pond.on('processfileprogress', async() => {
                    this.dispatchFormEvent('file-upload-started')
                })

                this.pond.on('processfile', async() => {
                    this.dispatchFormEvent('file-upload-finished')
                })

                this.pond.on('processfiles', async() => {
                    this.dispatchFormEvent('file-upload-finished')
                })

                this.pond.on('processfileabort', async() => {
                    this.dispatchFormEvent('file-upload-finished')
                })

                this.pond.on('processfilerevert', async() => {
                    this.dispatchFormEvent('file-upload-finished')
                })
            },

            dispatchFormEvent: function(name) {
                this.$el.closest('form')?.dispatchEvent(
                    new CustomEvent(name, {
                        composed: true,
                        cancelable: true,
                    })
                )
            },

            getUploadedFileUrls: async function() {
                const uploadedFileUrls = await getUploadedFileUrlsUsing()

                this.fileKeyIndex = uploadedFileUrls ?? {}

                this.uploadedFileUrlIndex = Object.entries(this.fileKeyIndex)
                    .filter(value => value)
                    .reduce((obj, [key, value]) => {
                        obj[value] = key

                        return obj
                    }, {})
            },

            getFiles: async function() {
                await this.getUploadedFileUrls()

                console.log(posterFileUrl);

                let files = []

                for (const uploadedFileUrl of Object.values(this.fileKeyIndex)) {
                    if (!uploadedFileUrl) {
                        continue
                    }

                    files.push({
                        source: uploadedFileUrl,
                        options: {
                            type: 'local',
                        },
                        file: {
                          name: "hedgehog.jpg",
                          size: 189397, //correct size of the file
                          type: "image/png"
                        },
                        metadata: {
                            poster: posterFileUrl,
                        },
                    })
                }

                return shouldAppendFiles ? files : files.reverse()
            }
        }
    })
}