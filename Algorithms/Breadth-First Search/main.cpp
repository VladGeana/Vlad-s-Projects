#include <iostream>
#include<fstream>
#include<vector>
#define Nmax 100001
#define mx 1000000000
typedef int coada[Nmax];
using namespace std;
ifstream f("bfs.in");
ofstream g("bfs.out");
coada c;
int cst[Nmax],n,m,x,y,s,i;
vector<int>v[Nmax];
void BFS(int nod)
{
    int siz=1,start=1;
    c[start]=nod;
    cst[nod]=0;
    while(siz-start>=0)
    {
        for(int j=0;j<v[c[start]].size();j++)
            if(cst[v[c[start]][j]] > cst[c[start]]+1)
        {
            cst[v[c[start]][j]] = cst[c[start]]+1;
            c[++siz]=v[c[start]][j] ;
        }
        start++;
    }
}
int main()
{
    f>>n>>m>>s;
    for(i=1;i<=m;i++)
    {
        f>>x>>y;
        if(x!=y)
            v[x].push_back(y);
    }
    for(i=1;i<=n;i++)
        cst[i]=mx;
    BFS(s);
    for(i=1;i<=n;i++)
        if(cst[i]!=mx) g<<cst[i]<<' ';
        else g<<-1<<' ';
}
