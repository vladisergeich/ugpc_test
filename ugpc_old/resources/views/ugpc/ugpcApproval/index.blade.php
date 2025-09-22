@extends('layouts.portal')

@section('content')

<div style="width: 100%; max-width: 2500px; margin: auto">
    <div style="display: block; text-align: right;">ЗАЯВКА НА ГРАВИРОВКУ ВАЛОВ ГЛУБОКОЙ ПЕЧАТИ</div>
    <div style="display: block; text-align: right;">
        <div style="display: inline-block; text-align: right;">Статус заказа:</div>      
        <div style="display: inline-block; text-align: right;">Технолгогическая оснастка</div> 
    </div>  

    <table style="width: 100%;" class="iksweb">
        <tbody>
            <tr>
                <td colspan="3">Комментарий для поставщика гравировки</td>
                <td>№ Заказа (ОКВИД)</td>
                <td>Внутренний № Партии</td>
                <td colspan="3" rowspan="11">
                    <img src="data:image/jpeg;base64,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
                    B/Y3/5Skfta/wDcr/8Apvkr6+rsx38Rei/I+g4l/wB7h/17o/8ApqAV4p4K/wCUgfxE/wCxI8N/+lmuV7XXingr/lIH8RP+xI8N/wDpZrlThtp/4X+aOPLf4eI/wf8At8D2uiiiuU8s8Y/b2GfgBa/9jj4V/wDUh06rdZP/AAUX1B9J/ZhkuowrSW3inwxKoboSuv6eRn8q8mP7SOuE/wDHppP/AH6k/wDi6+uyPLq2JwzdO2kn+SPF4uzihgsuw6rX1nU2V9o0z2+s39j/AP5HP42f9jzB/wCo7oleRf8ADSOuf8+mk/8AfqT/AOLr0H/gnx4kn8Xf8Lg1C5WGOa48cx7liBCjboGjKMZJPQDvWmcZXXw+FlOpa2i/Ex4IzvD4yGMp0r39nF6r/p5A+ih1ooHWivjT6AO9FHeigDzb43/CLU/iX8T/AIO61YzWMNr8PfGFxr+opO7LJPBJoGsaaqxAKQ0nnahC2GKjYshyWAVvSR0ryn9oX4ka14F+LvwJ0vS737LY+MvHN1o+sReSj/bLVPDWu3yx5ZSUxcWds+5Crfu9udrMrerKNooAKKKKACiiigDk/jv8HtM/aE+CHjLwDrU99a6P440K+8P381k6x3UMF3bvBI0TOrKJArkqWVgCBkEcV+af7eOn61+z/wD8Ebv2J/HGv+G9ci0/9n3xL8N/FfjbT/JSDU9LtLOyFtNH5Fw8bGcXM8MPlHDB3y21Vdl/Vg9K/OLWfjMv7Vf/AASc/bq0XxZpv9tf8Ku8TfEzw7bz6rcHUvtX2OS51OxmVZE/c/ZftFvFCoLeV9iiZGXCqjQH6OJ92lr5i/4IzftH6n+1f/wTA+DXjXWlvm1i60M6Vf3F7ftfXWoXGnzy6fJdyzMoZpLh7UzNuyQZSCz43t9O0gCiiigAooooAK+cE/ZD8TH/AIK4/wDC/Pt2h/8ACH/8Kg/4QD7H50v9pfb/AO2ft/mbPL8vyPK+Xd5m/dxsx81fR9fKi/tIeNP+H3n/AAqH+2v+Ld/8KN/4TD+yfskH/IV/t/7J9o87Z53+o+TZv8vvt3c0AfVZ6V8Of87KP/dtH/u019xnpXw5/wA7KP8A3bR/7tNAH3HRRRQB/FZ+0j/yWfWPpB/6Ijr7F/4Njv8AlMJ4A/7Bmsf+m6evjr9pH/ks+sfSD/0RHX2N/wAGx3/KYTwB/wBgzWP/AE3T16tT+PP0l/6Sz7rMf+SkxfpX/wDTUz+o2vKf27f+TIPjJ/2I2t/+kE9erV5T+3b/AMmQfGT/ALEbW/8A0gnrz6H8SPqj5nJP+Rjh/wDHD/0pEP7AX/JjHwb/AOxK0f8A9Ioq9cryP9gL/kxj4N/9iVo//pFFXrlPE/xper/MrPv+RniP+vk//SmfJf8AwVp/5A/7Pf8A2Wzw5/6DdV9aV8l/8Faf+QP+z3/2Wzw5/wCg3VfWlaVP4MP+3v0N8d/yLML61PzQV47/AMFB/wDkxn4vf9idq3/pFNXsVeO/8FB/+TGfi9/2J2rf+kU1Z4b+LH1RyZP/AL/R/wAcfzR6to3/ACBbT/rin8hRRo3/ACBbT/rin8hRWdT4mcFT436lym/xU6m/xVJB8h/sb/8AKUj9rX/uV/8A03yV9fV8g/sb/wDKUj9rX/uV/wD03yV9fV2Y7+IvRfkfQcS/73D/AK90f/TUArxTwV/ykD+In/YkeG//AEs1yva68U8Ff8pBPiJ/2JHhv/0s1ypw20/8L/NHHlv8PEf4P/b4HtdFFFcp5Z88/wDBUzW4vDX7F+talOsjQafrvh65kWMAsyprlgxAyQM4HGSK+GR+2t4VP/MP8Qf9+If/AI7X3H/wVdsYdQ/YJ8cJMu5Vk0yQDOMMup2jKfwIFfj5mv3rwqynCYrLatTEvVTaWtvsxf6n81+P3GWNwNXB5ZgYu6U5t8t1aTUUk+65HfyaPqD/AIbW8K/9A/xB/wB+If8A47X11/wSR8bWvxF+GvxN1ixjuIrW88cnYs6hZBt0bSUOQCR1U9+lflLur9Qf+CIdnFafss+JpI12tc+L7iWQ5J3MLGxXPt8qgceldviZkuDw2Te1wz15o9b6ank+AnG2YV85xGAzCLtVpNRajZJxnCWr7WT+dj7LHWikU5pa/ng/qoO9FHeigDzX446r4T0z4m/B2HxHpt9f6zqHi+4t/Cs8DlY9N1IaBrEjzSjzFDIbCO/iAKyDfPGdgIEkfpKHcteU/tD/AA41rxx8W/gXqmlWf2qx8G+OLrV9Yl86OP7Jav4a1yyWTDMC+bm8to9qBmHmbsbVZh6sh3KKAFooooAKKKKAA9K+C/2HfhB4e/ZK/wCC0H7V3ha1nsrG4+NGheH/AIoaJpsL3Vw5gE+oWuqzTSSKUjc6lM0gjEhGy6TywFR0i+9DyK+Yfjjfab8M/wDgqv8AAHWpPC9/Nd/ELwf4t8Ct4gs9NVo4Z420vWLS2vLngrH5On6q0MeWO9pSqYMrqAH/AASV+Emmfsw/srXnwZ0+a+kk+D/i/X/D8qXrrLcx28+ozappzSyxqsUjzaXqGn3DGMYU3BRlR0eNPp6vin9hXxPql3/wV/8A26tFm1K+l0fT7nwJe2tg07ta208+gFJpUjJ2rJItvArsACwhjBzsXH2tQwCiiigAooooAK+b18XfCU/8Fcv7C/4RnXP+F6f8Kg+3jxF5rf2b/wAI3/bOz7Fs+0bfP+2fvN32fOzjzcfJX0hXymn7N/jb/h93/wALe/sX/i3I+B3/AAh/9rfbIP8AkK/299r+z+Rv87/UfP5mzZ23buKAPqw9K+HP+dlH/u2j/wB2mvuM9K+HP+dlH/u2j/3aaAPuOiiigD+Kz9pH/ks+sfSD/wBER19jf8Gx3/KYTwB/2DNY/wDTdPXzH/wUP8NWXgj9vf41eH9Lh+zaT4c8cazo2nQb2f7Pa2t7NbwR7mJZtsUaLuYljjJJJJP0P/wbX6lNY/8ABZ34RxRPtjvItbhmGAd6DRb5wPb5kU8enpmvQlWi6kqnRp/irH0WKzmjVzavj4p8tRVUl19+Eoq/zav5H9UVeU/t2/8AJkHxk/7EbW//AEgnr1avKf27f+TIPjJ/2I2t/wDpBPXJQ/iR9UcOSf8AIxw/+OH/AKUiH9gL/kxj4N/9iVo//pFFXrleR/sBf8mMfBv/ALErR/8A0iir1ynif40vV/mVn3/IzxH/AF8n/wClM+S/+CtP/IH/AGe/+y2eHP8A0G6r60r5L/4K0/8AIH/Z7/7LZ4c/9Buq+tK0qfwYf9vfob47/kWYX1qfmgrx3/goP/yYz8Xv+xO1b/0imr2KvHf+Cg//ACYz8Xv+xO1b/wBIpqzw38WPqjkyf/f6P+OP5o9W0f8A5Atp/wBcU/kKKNH/AOQLaf8AXFP5Cis6nxM4Knxv1LlN/ip1N/iqSD5D/Y3/AOUpH7Wv/cr/APpvkr6+r5B/Y3/5Skfta/8Acr/+m+Svr6uzHfxF6L8j6DiX/e4f9e6P/pqAV4p4K/5SB/ET/sSPDf8A6Wa5XtdeKeCv+UgfxE/7Ejw3/wClmuVOG2n/AIX+aOPLf4eI/wAH/t8D2uiiiuU8s8l/bl/aWb9j79lvxR8Rk0VfELeHfsmNPN19lE/n3cNv/rNj7dvnbvunO3HGcjwT9kv/AILgfB39oDRbK38WX9r8M/FVxLNG9hqszPYhUUusovvLSEIyjH70xtvBQA5Rn9//AG3f2Zf+Gxf2YfE3w4/tv/hHf+Ei+y/8TH7F9s+z+Rdw3H+q3x7t3k7fvjG7POMH8/T/AMGyvJ/4vZx7+D//ALur2svjl0sO44qTjO+jV9rLya3P2LgbA+HuLyOrR4przoYv2jcJwjOXuckbJpRlBrm5nbSWm6R+qEDR3EKyKsbK/KkDgg9/5VL5K9uOc8V+cHwZ/wCCD3jz9njXP7Q8D/tN+IPC88k0E866f4bkihvGhZmiE8Qv/LnRSzfJIrKQ7AjDHP6EfD7TNd0fwla2/iXVNN1rWo9/2i80/Tn0+3my5K7YXmmZMLtBzK2SCeAQo4MVRowf7mpzr0a/P/M+D4lyfKMDVSyjHRxUH19nUpyW26mrfdJ7dDaAxRRRXIfMh3oo70UAeU/tDfEjWvA3xe+BOl6XefZbHxl44udH1iPyY3+2WieGtdvljyykpi4s7Z9yFW/d7c7WZT6tXI/EXxF4T0Xxf4FtfEkdi+savrktp4WM9mbiSPUhpt9M5hcIfIk+wQ34MhKZQyR7iZArdZH92gB1FFFABRRRQAV8U/8ABanwvqXiDT/2V7uxsL68tdD/AGjfBd7qM1vA8kdhbtNcQCWVlBEaGaeGIM2AXmjXOWUH7Wr5x/4K/wDw30X4rf8ABLn4+6Xr1l/aFja+BtU1iOLzXj23Vjbve2smUIJ8u4t4X2k7W2bWDKSpFuBtKf8AhBv+ClX/AD9f8LQ+Gf8A1z/sz/hHNU/HzftH/CU/7HlfYf8Alp537r3SvyZ1z9tGf9pb4if8EzP2oNS8O30Og61reveB9aNlHDELLxBq9qukxCKCS4Zzatd2t5IG3uywQgsPMZUb9Zh0psAooopAFFFFABXyov7SHjQf8Fu/+FQ/2z/xbv8A4Ud/wmH9k/Y4P+Qr/b32T7R52zzv9R8nl7/L77d3NfVdeUR/EP4Tn9t3/hE/s+hf8L0/4Qb+1hMNFb+0v+Eb+3+Vt+3eVt8j7Zz9n83O759mPmoA9XPSvhz/AJ2Uf+7aP/dpr7jPSvhz/nZR/wC7aP8A3aaAPuOiiigD+OX/AIKd/wDKSn9ob/spniT/ANOlzXuH/Btz/wAppfgz/wBxz/0xahXh/wDwU7/5SU/tDf8AZTPEn/p0ua9w/wCDbn/lNL8Gf+45/wCmLUK2+yZn9VVeU/t2/wDJkHxk/wCxG1v/ANIJ69Wryn9u3/kyD4yf9iNrf/pBPSofxI+qPYyT/kY4f/HD/wBKRD+wF/yYx8G/+xK0f/0iir1yvI/2Av8Akxj4N/8AYlaP/wCkUVeuU8T/ABper/MrPv8AkZ4j/r5P/wBKZ8l/8Faf+QP+z3/2Wzw5/wCg3VfWlfJf/BWn/kD/ALPf/ZbPDn/oN1X1pWlT+DD/ALe/Q3x3/IswvrU/NBXjv/BQf/kxn4vf9idq3/pFNXsVeO/8FB/+TGfi9/2J2rf+kU1Z4b+LH1RyZP8A7/R/xx/NHq2j/wDIFtP+uKfyFFGj/wDIFtP+uKfyFFZ1PiZwVPjfqXKb/FTqb/FUkHyH+xv/AMpSP2tf+5X/APTfJX19XyD+xv8A8pSP2tf+5X/9N8lfX1dmO/iL0X5H0HEv+9w/690f/TUArxTwV/ykD+In/YkeG/8A0s1yva68U8Ff8pBPiJ/2JHhv/wBLNcqcNtP/AAv80ceW/wAPEf4P/b4HtdFFFcp5Z5X+2t8a9W/Z3/Zl8S+MdDi0+bVNH+y+Ql7G0kDebdwwtuVWUn5ZGxhhzjr0PyP+z3/wWqu7zxS9p8TtF0+10u4EaW99odrLutHLgM00ckrlo9rbiY/nXYQEcsNv3V8VvhZoPxq8A3/hnxNY/wBpaHqXl/abbz5IfM2SLInzxsrjDop4IzjByCRXjq/8EsPgPt/5ET/ytah/8fr7fh/MuHaWX1MLm9Cc6kpXU4WulZaXcl1T0s1qfl/GGS8Y182pY3h3F06dKMEpQqOXLKXNJttRhLo0rpp6Ho3wP/af8CftH6de3XgzxBb6ymmyLFdIIZYJYCwypaORVba2DhsbSVYAkqQO+ByK8J8Nf8E1/gz4L1uDVNH8K32k6la58m7s/EOpwzxZBVtrrcAjIYg4PIJHc17qi7FwM/ia+azRYD218uc+TtNRUl84tp+tl6H2uQyzh4dLOY01UXWk5OL+U4pr0vL1FooorzT2w70Ud6KAPKf2hfhtrXjj4u/ArVNKs/tVj4N8cXOsazL50cf2S0fw1rtksmGYF83N5bJtQM37zdjarMvq1eU/tDfErWvAvxd+BOl6XffZbHxl44udH1iIxRv9stU8Na7fLHllJTFxZ2z7kKt+7252syn1bNABRRRQAUUUUAFZ/ivwxpvjbw1qGi6zp9jq2j6xbSWV/Y3tutxbXsEilJIpY3BV0dGZWVgQwJBBBxWhSNjvQB+OX7RvwZ/4ZZ/4NhNe0G01IX3iL4I+OL+30DxJDb/ZL2wv7H4gXFmmo2pDs9pOYzKA0cm9FmdQ5BJP6q/swfGf/ho/9mr4e/ET+zf7H/4Tzw1pviL7ALj7R9h+2Wsdx5Pm7V8zZ5m3ftXdjO0ZwPlT4Oj/AIac/bH/AOCg3wD14fY/B2pf8I95l5p/7vU1/tvwnBYXW2R98Y2RafCY8xHa7OW3gqq73/BAL4e6h8Kv+CR/wl0DVLjQ7q/sP7Y82XR9as9asnL6zfSDy7uzllt5eGAOyRtrBlbDKyimB9jUUUVIBRRRQAV8qj9m7xr/AMPu/wDhb39i/wDFu/8AhR3/AAh/9rfa4P8AkK/299r+z+Tv87/UfP5mzy+27dxX1VXyov7SnjRv+C3f/CoP7a/4t3/wo3/hMf7J+yQf8hX+3vsn2nztnnf6j5Nm/wAvvt3c0AfVZ6V8Of8AOyj/AN20f+7TX3GelfDn/Oyj/wB20f8Au00AfcdFFFAH8cv/AAU7/wCUlP7Q3/ZTPEn/AKdLmvcP+Dbn/lNL8Gf+45/6YtQrw/8A4Kd/8pKf2hv+ymeJP/Tpc17h/wAG3P8Ayml+DP8A3HP/AExahW32TM/qqryn9u3/AJMg+Mn/AGI2t/8ApBPXq1eU/t2/8mQfGT/sRtb/APSCelQ/iR9Uexkn/Ixw/wDjh/6UiH9gL/kxj4N/9iVo/wD6RRV65Xkf7AX/ACYx8G/+xK0f/wBIoq9cp4n+NL1f5lZ9/wAjPEf9fJ/+lM+S/wDgrT/yB/2e/wDstnhz/wBBuq+tK+S/+CtP/IH/AGe/+y2eHP8A0G6r60rSp/Bh/wBvfob47/kWYX1qfmgrx3/goP8A8mM/F7/sTtW/9Ipq9irx3/goP/yYz8Xv+xO1b/0imrPDfxY+qOTJ/wDf6P8Ajj+aPVtH/wCQLaf9cU/kKKNH/wCQLaf9cU/kKKzqfEzgqfG/UuU3+KnU3+KpIPkP9jf/AJSkfta/9yv/AOm+Svr6vkH9jf8A5Skfta/9yv8A+m+Svr6uzHfxF6L8j6DiX/e4f9e6P/pqAV4p4K/5SB/ET/sSPDf/AKWa5XtdeKeCv+UgfxE/7Ejw3/6Wa5U4baf+F/mjjy3+HiP8H/t8D2uiiiuU8s+Vf+C2n7SPjT9kb/gmL8TPiH8PNa/4R7xh4f8A7K+wah9kgu/I87VrO3l/dzo8TbopZF+ZTjdkYIBH5/8A/BOb/g7N0XxAnhvwh+0ZoI8PzW+myRah8QNMZ7iC+uo+Y3n0yCAvD5sY2s8DSL55BEMUTnyf2N+JXwv8NfGbwTfeGvGHh3Q/FXh3Utn2vS9YsIr6yutkiyJ5kMqsjbZERxkHDKpHIBrypf8AgmJ+zXjn9nr4G5/7ETS//jFUrdQOq/Zx/bB+Fv7Xnho6t8MfH3hXxvaxW1td3SaVqEc9zpyXKM8IuoAfNtpGCOPLmRHDRupUMrAekA5FeGt/wTD/AGa2/wCbevgb/wCEJpf/AMYr3IDAqfQAooooAO9FHeigDkfiL4d8Ja14u8C3HiOSxTWNI1yW78LCe9NvI+pHTb6GQQoGXz5PsM1+fLIcBBJJtzGHXrIxhfXk9a8r/aG+G+teOfi78CdU0uy+1WPgzxxc6xrEnmon2S1fw1rtismGYF83N5bJtQM37zdjarMPVgc0AFFFFABRRRQAVn+KvE+m+CfDWoa1rWpWOj6PpFrLe319ezpb2tlBGheSWWRyFSNFUszMQAASTgVoV8b/APBdH4j61ov7DP8AwrvwrfDS/GH7QniXS/hRol5NFHJZW8mqzbLj7UWV2SB7OO7jLxRySK0qFVB+dQD5vX9vbSfgP/wR5+Nn7Yvhu4aP4ifH7xNqj6Dcaymm2Opx7NQl0TRLVFSF0n/s/TrRbr7M/n73ivWL7ZHcfXn/AARm/Zz1P9lL/gl/8G/BetNfLrNvoZ1a/t73T3sLnT7jUJ5dQktJYXJZZLd7owNuwSYiSqZ2L4z8Rf2MrX4W/to/sF/CbwbpP2/4W/CXTfFXiDU9PuzbLA0ljY2NtZ6vcQAJFPfLqGoJIJki81ZruWVQmZGH6AqNq1TELRRRUjCiiigArylfh78Jf+G3v+Er+0aH/wAL0/4Qf+yfI/tpv7S/4Rv7f5u/7B5u3yPtnH2jys7/AJN+Plr1avlRf2cPGh/4Lef8Le/sX/i3f/Cjf+EP/tf7XB/yFf7f+1/ZvJ3+d/qPn3+X5fbdu4oA+qz0r4c/52Uf+7aP/dpr7jPSvhz/AJ2Uf+7aP/dpoA+46KKKAP45f+Cnf/KSn9ob/spniT/06XNe4f8ABtz/AMppfgz/ANxz/wBMWoV4f/wU7/5SU/tDf9lM8Sf+nS5r3D/g25/5TS/Bn/uOf+mLUK2+yZn9VVeU/t2/8mQfGT/sRtb/APSCevVq8p/bt/5Mg+Mn/Yja3/6QT0qH8SPqj2Mk/wCRjh/8cP8A0pEP7AX/ACYx8G/+xK0f/wBIoq9cryP9gL/kxj4N/wDYlaP/AOkUVeuU8T/Gl6v8ys+/5GeI/wCvk/8A0pnyX/wVp/5A/wCz3/2Wzw5/6DdV9aV8l/8ABWn/AJA/7Pf/AGWzw5/6DdV9aVpU/gw/7e/Q3x3/ACLML61PzQV47/wUH/5MZ+L3/Ynat/6RTV7FXjv/AAUH/wCTGfi9/wBidq3/AKRTVnhv4sfVHJk/+/0f8cfzR6to/wDyBbT/AK4p/IUUaN/yBbT/AK4p/IUVnU+JnBU+N+pcpv8AFTqb/FUkH5qy/wDBR34M/wDBPn/gqF+0lJ8XvGX/AAiKeLG8PrpR/sm+v/tRt9PHnf8AHrDLs2+dH9/Gd3GcHHpP/ESN+xb/ANFm/wDLR13/AOQq/Gj/AIOajn/gpd4s/wCu8P8A6bdPr87a9DGRvUXovyPouKY8uKptdaVL/wBNxX6H9VP/ABEjfsW/9Fm/8tHXf/kKul/YQ/bU+Gf7d37W/wAT/GPwp8S/8JV4bsfDPh7SZrz+zrqx2XMdxrEjx7LmKNzhJozuC7TuxnIIH8mNfut/wZyHHg743f8AX9pX/ou6rOjGym/7r/NHJlMVKlim+lP/ANvgv1P22ooorjPIDNFfDn/ByL/yhc+M3v8A2IP/ACu6dX8q1VGNxNn9xlGea/hzr+jb/g0I/wCUbHjf/spl/wD+mvSqJRsCZ+q1FFFSMO9FITjP0r5w+KH7QP7RXwp+G3iHxRf/AAj+Bslj4b0u51W5T/hcd5a7o4Imlcebc6BFbx8IfnnljjXq7ooLAA7n9ob4j614H+LnwK0vSrz7LY+MfHNzpGsxeTHJ9rtU8Na5fLHllJTFzZ20m5Crfu9udrMp9XXpXzf+z1+0v4L/AGxvgV+zr8UPFNnoem65431OTUvB1jpOtT6xBYaq2kaqJLdriOGFTPHpw1JJkmjCRTJLGC0iRsfo9eFoAWiiigAooooAD0r4o+KPiLwn+2V/wWK8D/D0x+K9Wtf2X9CvvGniG3NkT4ci8Q362EeipceYrJLdQ2kt/dQsoVo2KtHKSlxGv2sWxX5qf8E/vhBqX7CXw9/bc/avvJrHxRo/xK1vxD468OaNau8C32kaXc6xcWtz9rKspjv0mMkTxpJH9nMEytJ52xGgPcf2MvD/AIsu/wDgqb+2Z4k1aS+ufCf2nwZ4Z0CW4vRNHbvaaH9turaGIsWhjVtVjmI2qjPdSMpZvMx9d183/wDBJLwD8QPh7/wT6+H8fxUbf8RvEI1HxX4gDWptZorvVtSutUeOaExxeTOn2wLLEEVUkV1XKqCfpCkAUUUUAFFFFABXynH+0j41/wCH3f8AwqH+2P8Ai3X/AAo7/hMf7K+yQf8
                    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" alt="" />
                </td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3" rowspan="3"><textarea style="border: 1px solid black; "></textarea></td>
                <td><input style="width: 30%" type="text"></td>
                <td><input style="width: 30%" type="text"></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2">Заявка на отправку валов:<input style="width: 30%" type="text"></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2">Заявка на переброс валов:<input style="width: 30%" type="text"></td>
                <td></td>
            </tr>
            <tr>
                <td>Виды работ:</td>
                <td>Материалы заказчика:</td>
                <td>Условия печати</td>
                <td colspan="2">Репро: <input style="width: 30%" type="text"></td>
                <td></td>
            </tr>
            <tr>
                <td><input style="width: 10%" type="checkbox"> Новая работа</td>
                <td><input type="checkbox"> Электронный файл</td>
                <td>мат-л печати</td>
                <td colspan="2">Гравируем в: <input style="width: 30%" type="text"></td>
                <td></td>
            </tr>
            <tr>
                <td><input type="checkbox"> Перегравировка</td>
                <td><input type="checkbox">Промообразец</td>
                <td><input style="width: 30%" type="text"></td>
                <td colspan="2">Базы везут из:<input style="width: 30%" type="text"></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="2"></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="2"></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="2"></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="2"></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="2"></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="2"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td colspan="3"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    
    <table>
        <tr>
            <td colspan="3">
                <label style="display: block;">Комментарий для поставщика гравировки</label>
                <textarea style="border: 1px solid black; display: block;"></textarea>
            </td>
            <td>
                <label style="display: block;">№ Заказа (ОКВИД)</label>
                <input type="text">
            </td>
            <td>
                <label style="display: block;">Внутренний № Партии</label>
                <input type="text">
            </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>
                <label style="display: block;">Вид работы:</label>
                <div style="display: block;">
                    <input type="checkbox">
                    <label>Новая работа</label>
                </div>
                <div style="display: block;">
                    <input type="checkbox">
                    <label>Перегравировка</label>
                </div>
                <div style="display: block;">
                    <input type="checkbox">
                    <label>Гравировка с изм.</label>
                </div>
                <div style="display: block;">
                    <input type="checkbox">
                    <label>Изм. диаметра</label>
                </div>
                <div style="display: block;">
                    <input type="checkbox">
                    <label>Перехромирование</label>
                </div>
                <div style="display: block;">
                    <input type="checkbox">
                    <label>Монтаж</label>
                </div>
                <div style="display: block;">
                    <input type="checkbox">
                    <label>Цветопроба</label>
                </div>
                <div style="display: block;">
                    <input type="checkbox">
                    <label>Пробопечать</label>
                </div>
            </td>
            <td>
                <div style="display: block;">
                    <label>Материалы заказчика:</label>
                    <div style="display: block;">
                        <input type="checkbox">
                        <label>Электронный файл</label>
                    </div>
                    <div style="display: block;">
                        <input type="checkbox">
                        <label>Промообразец</label>
                    </div>
                </div>
                <div style="display: block;">
                    <label>Технологические элементы:</label>
                    <div>
                        <label>метки прив</label>
                        <label>"Светофор"</label>
                        <label>Кресты</label>
                        <label>Линия резки</label>
                    </div>
                    <div>
                        <div> 
                            <label>L</label>
                            <input type="checkbox">
                            <input type="checkbox">
                            <input type="checkbox">
                            <input type="checkbox">
                        </div>
                        <div> 
                            <label>R</label>
                            <input type="checkbox">
                            <input type="checkbox">
                            <input type="checkbox">
                            <input type="checkbox">
                        </div>
                    </div>
                </div>
            </td>
            <td>
                <label style="display: block;">Условия печати:</label>
                <div style="display: block;">
                    <label style="display: block;">мат-л печати</label>
                    <input type="text">
                </div>
                <div style="display: block;">
                    <label style="display: inline-block;">ширина:</label>
                    <input type="text">
                </div>
                <div style="display: block;">
                    <label style="display: block;">мат-л ламинации</label>
                    <input type="text">
                </div>
                <div style="display: block;">
                    <label style="display: block;">Серия красок</label>
                    <input type="text">
                </div>
            </td>
            <td colspan="2">
                <div style="display: block;">
                    <label>Заявка на отправку валов</label>
                    <input type="text">
                </div>
                <div style="display: block;">
                    <label>Заявка на переброс валов</label>
                    <input type="text">
                </div>
                <div style="display: block;">
                    <label>Репро:</label>
                    <input type="text">
                </div>
                <div style="display: block;">
                    <label>Гравируем в:</label>
                    <input type="text">
                </div>
                <div style="display: block;">
                    <label>Базы привезут из:</label>
                    <input type="text">
                </div>
                <div style="display: block;">
                    <label>Формат:</label>
                    <input type="text">
                </div>
                <div style="display: block;">
                    <label>Цилиндров:</label>
                    <input type="text">
                </div>
                <div style="display: block;">
                    <label>Степпинг:</label>
                    <input type="text">
                </div>
                <div style="display: block;">
                    <label>Печать:</label>
                    <input type="text">
                </div>
                <div style="display: block;">
                    <label>Число стадий:</label>
                    <input type="text">
                </div>
                <div style="display: block;">
                    <label>Цвет линии реза:</label>
                    <input type="text">
                </div>
                <div style="display: block;">
                    <label>ICC:</label>
                    <input type="text">
                </div>
            </td>
            <td colspan="2"></td>
            <td></td>
        </tr>
    </table>
    
    
</div>  


@endsection
