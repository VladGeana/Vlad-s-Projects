#include <iostream>
#include<fstream>
#include<cmath>
using namespace std;
int d[100001][30],w[100001],n,m,i,q,a,b,sz;
ifstream f("rmq.in");
ofstream g("rmq.out");
void buildRMQ()
{
    int i,j;
    for(i=1;i<=sz;i++)
        d[i][0]=i;
    for(j=0;(1<<j)<=sz;j++)
        for(i=1;i+(1<<j)<=sz+1;i++)
    {
        if(w[d[i][j]] < w[d[i+(1<<j)][j]])
            d[i][j+1] = d[i][j];
        else
            d[i][j+1]=d[i+(1<<j)][j];
    }
}
int querry(int i,int j)
{
    int k=log2(j-i+1);
    if(w[d[i][k]]<w[d[j-(1<<k)+1][k]])
        return d[i][k];
    else
        return d[j-(1<<k)+1][k];
}
int main()
{
    f>>n>>m;
    for(i=1;i<=n;i++)
        f>>w[i];
    sz=n;
    buildRMQ();
    while(m--)
    {
        f>>a>>b;
        g<<querry(a,b)<<'\n';
    }
}

