#include <iostream>
#include<fstream>
#include<math.h>
#define PI 3.14159265
using namespace std;
ifstream f("infasuratoare.in");
ofstream g("infasuratoare.out");
struct coord
{
    double x,y;
};
coord c[121000];
int n,i,pct,nxt,j,cur;
double panta_max,x,y,mn=10000000,x1,y1;
double panta(double x1,double y1,double x2,double y2)
{
    double m = ( (y1-y2)/(x1-x2) );
    double cos=sqrt(1/(m*m+1));
    return (acos(cos) *180/PI);
}
int main()
{
    f>>n;
    for(i=1;i<=n;i++)
    {
        f>>c[i].x>>c[i].y;
        if(c[i].x<mn)
        {
            mn=c[i].x;
            pct=i;
            x1=mn;
            y1=c[i].y;
        }
    }
    while(nxt!=pct)
    {
        cout<<x1<<' '<<y1<<'\n';
        panta_max=-10000000;
        for(i=1;i<=n;i++)
            if( x1!=c[i].x && panta(x1,y1,c[i].x,c[i].y)>panta_max)
        {
            panta_max=panta(x1,y1,c[i].x,c[i].y);
            nxt=i;
        }
        x1=c[nxt].x;
        y1=c[nxt].y;
    }
}
