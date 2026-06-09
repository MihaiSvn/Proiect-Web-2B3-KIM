document.addEventListener(
    'DOMContentLoaded',
    () => {

        const canvas =
            document.getElementById(
                'trainersChart'
            );

        if(canvas){

            new Chart(
                canvas,
                {
                    type:'bar',

                    data:{
                        labels:trainerLabels,

                        datasets:[
                            {
                                data:trainerValues,

                                backgroundColor:
                                    '#d0a2a2',

                                borderRadius:12
                            }
                        ]
                    },

                    options:{

                        indexAxis:'y',

                        maintainAspectRatio:false,

                        plugins:{
                            legend:{
                                display:false
                            }
                        },

                        scales:{

                            x:{
                                beginAtZero:true,

                                grid:{
                                    color:'#f1e7e7',
                                    borderDash:[5,5]
                                }
                            },

                            y:{
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
                'exportTrainersCsv'
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

                                report:'trainers',

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
                                'top_trainers.csv';

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
                'exportTrainersXml'
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

                                report:'trainers',

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
                                'top_trainers.xml';

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
                'exportTrainersPng'
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
                        'top_trainers.png';

                    a.click();

                }
            );

        }

        const webpButton =
            document.getElementById(
                'exportTrainersWebp'
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
                        'top_trainers.webp';

                    a.click();

                }
            );

        }

    }
);