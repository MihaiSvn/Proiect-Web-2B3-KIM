document.addEventListener(
    'DOMContentLoaded',
    () => {

        const canvas =
            document.getElementById(
                'sessionsChart'
            );

        if(canvas){

            new Chart(
                canvas,
                {
                    type:'bar',

                    data:{
                        labels:sessionsLabels,

                        datasets:[
                            {
                                data:sessionsValues,

                                backgroundColor:
                                    '#d0a2a2',

                                borderRadius:12
                            }
                        ]
                    },

                    options:{

                        maintainAspectRatio:false,

                        plugins:{
                            legend:{
                                display:false
                            }
                        },

                        scales:{

                            x:{
                                grid:{
                                    color:'#f1e7e7',
                                    borderDash:[5,5]
                                }
                            },

                            y:{
                                beginAtZero:true,

                                grid:{
                                    color:'#f1e7e7',
                                    borderDash:[5,5]
                                }
                            }
                        }
                    }
                }
            );

        }

        const csvButton =
            document.getElementById(
                'exportCsv'
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

                                report:'sessions',

                                format:'csv'

                            })
                        }
                    )
                        .then(response =>
                            response.blob()
                        )
                        .then(blob => {

                            const url =
                                window.URL.createObjectURL(
                                    blob
                                );

                            const a =
                                document.createElement(
                                    'a'
                                );

                            a.href = url;

                            a.download =
                                'sessions.csv';

                            a.click();

                            window.URL.revokeObjectURL(
                                url
                            );

                        });

                }
            );

        }

        const xmlButton =
            document.getElementById(
                'exportXml'
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

                                report:'sessions',

                                format:'xml'

                            })
                        }
                    )
                        .then(response => response.blob())
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
                                'sessions.xml';

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
                'exportPng'
            );

        if(pngButton){

            pngButton.addEventListener('click',
                () => {

                    const url = canvas.toDataURL('image/png');

                    const a = document.createElement('a');

                    a.href = url;
                    a.download = 'sessions.png';

                    a.click();
                }
            );
        }

        const webpButton =
            document.getElementById(
                'exportWebp'
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
                        'sessions.webp';

                    a.click();
                }
            );
        }

    }
);