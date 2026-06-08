document.addEventListener(
    'DOMContentLoaded',
    () => {

        const canvas =
            document.getElementById(
                'distributionChart'
            );

        if(!canvas){
            return;
        }

        new Chart(
            canvas,
            {
                type:'pie',

                data:{
                    labels:
                    distributionLabels,

                    datasets:[
                        {
                            data:
                            distributionValues,

                            backgroundColor:[
                                '#d7a4a4',
                                '#b6a3d9',
                                '#aebfc8',
                                '#cfc7bd'
                            ],

                            borderColor:'#ffffff',

                            borderWidth:3
                        }
                    ]
                },

                options:{

                    maintainAspectRatio:false,

                    plugins:{

                        legend:{

                            position:'bottom',

                            labels:{

                                padding:20,

                                font:{
                                    size:14
                                }
                            }
                        }
                    }
                }
            }
        );

        const csvButton =
            document.getElementById(
                'distributionExportCsv'
            );

        if(csvButton){

            csvButton.addEventListener(
                'click',
                () => {

                    fetch(
                        '/kim/api/reports/export',
                        {
                            method:'POST',

                            headers:{
                                'Content-Type':
                                    'application/json'
                            },

                            body:JSON.stringify({

                                report:'distribution',

                                format:'csv'

                            })
                        }
                    )
                        .then(response =>
                            response.blob()
                        )
                        .then(blob => {

                            const url =
                                URL.createObjectURL(
                                    blob
                                );

                            const a =
                                document.createElement(
                                    'a'
                                );

                            a.href = url;

                            a.download =
                                'distribution.csv';

                            a.click();

                            URL.revokeObjectURL(
                                url
                            );
                        });

                }
            );
        }

        const xmlButton =
            document.getElementById(
                'distributionExportXml'
            );

        if(xmlButton){

            xmlButton.addEventListener(
                'click',
                () => {

                    fetch(
                        '/kim/api/reports/export',
                        {
                            method:'POST',

                            headers:{
                                'Content-Type':
                                    'application/json'
                            },

                            body:JSON.stringify({

                                report:'distribution',

                                format:'xml'

                            })
                        }
                    )
                        .then(response =>
                            response.blob()
                        )
                        .then(blob => {

                            const url =
                                URL.createObjectURL(
                                    blob
                                );

                            const a =
                                document.createElement(
                                    'a'
                                );

                            a.href = url;

                            a.download =
                                'distribution.xml';

                            a.click();

                            URL.revokeObjectURL(
                                url
                            );
                        });

                }
            );
        }

        const pngButton =
            document.getElementById(
                'distributionExportPng'
            );

        if(pngButton){

            pngButton.addEventListener(
                'click',
                () => {

                    const url =
                        canvas.toDataURL(
                            'image/png'
                        );

                    const a =
                        document.createElement(
                            'a'
                        );

                    a.href = url;

                    a.download =
                        'distribution.png';

                    a.click();
                }
            );
        }

        const webpButton =
            document.getElementById(
                'distributionExportWebp'
            );

        if(webpButton){

            webpButton.addEventListener(
                'click',
                () => {

                    const url =
                        canvas.toDataURL(
                            'image/webp'
                        );

                    const a =
                        document.createElement(
                            'a'
                        );

                    a.href = url;

                    a.download =
                        'distribution.webp';

                    a.click();
                }
            );
        }

    }
);